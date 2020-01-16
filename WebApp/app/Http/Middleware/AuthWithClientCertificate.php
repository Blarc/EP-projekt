<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use RuntimeException;
use function abort;
use function count;
use function preg_match;

class AuthWithClientCertificate
{
    /**
     * The authentication factory instance.
     *
     * @var Auth
     */
    protected $auth;

    private $publicPaths = array(
        '//',
        '/shopShowItem/'
    );

    private $userPaths = array(
        '/home/',
        '/edit-profile/',
    );

    private $adminPaths = array(
        '/seller-edit-profile/[0-9]+/',
        '/seller-create/',
        '/changeProfileStatus/[0-9]+/'
    );

    private $sellerPaths = array(
        '/customer-edit-profile/[0-9]+/',
        '/changeProfileStatus/[0-9]+/',
        '/item-manage/',
        '/item-deactivated/',
        '/item-create/',
        '/seller/edit-item/[0-9]+/',
        '/item-show/[0-9]+/',
        '/seller/item/[0-9]+/edit/',
        '/customer-create/',
        '/seller/shoppingLists/',
        '/seller/sl/[0-9]+/delete/',
        '/seller/item/[0-9]+/delete/',
        '/seller/item/[0-9]+/activate/',
        '/seller/sl/[0-9]+/accept/',
        '/seller/sl/[0-9]+/stornate/',
        '/seller/sl-show/[0-9]+/',

    );

    private $customerPaths = array(
        '/shop/shoppingLists/',
        '/shop/shoppingLists/[0-9]+/',
        '/shop/sl/[0-9]+/checkout/',
        '/shoppingList-create/%d/',
        '/shop/add/[0-9]+/',
        '/shop/[0-9]+/',
        '/shop/delete/[0-9]+/[0-9]+/',
        '/shoppingList/amount/[0-9]+/[0-9]+/'
    );

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null|string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($this->auth->guard($guard)->check()) {
            $user = static::getUserFromCert($request);
            $path = $request->getPathInfo();

            if ($this->checkRole($user, $path)) {
                return $next($request);
            }
            return abort(401, 'Unauthorized!');
//            return abort(401, $path);
        }

        if (!$request->secure()) {
            return abort(400, 'The Client Certificate auth requires a HTTPS connection.');
        }

        /** If the certificate is valid, log in and remember the user: */
        if ($request->server('SSL_CLIENT_VERIFY') === 'SUCCESS') {
            $user = static::getUserFromCert($request);
            $path = $request->getPathInfo();

            $this->auth->guard($guard)->login($user, true);

            if ($this->checkRole($user, $path)) {
                return $next($request);
            }
            return abort(401, 'Unauthorized!');
//            return abort(401, $path);
        }

        throw new AuthenticationException('Unauthenticated.');
    }

    /**
     * Gets the user from cert.
     *
     * @param Request $request
     * @return App\User
     * @throws RuntimeException
     */
    protected static function getUserFromCert(Request $request)
    {
        /**
         * Probably misconfigured Nginx:
         * @see https://nginx.org/en/docs/http/ngx_http_ssl_module.html#var_ssl_client_s_dn
         */
        if (empty($subject = $request->server('SSL_CLIENT_S_DN'))) {
            throw new RuntimeException('Missing SSL_CLIENT_S_DN param');
        }

        $email = self::getEmailFromDn($subject);

        if (empty($user = User::where('email', '=', $email)->first())) {
            return abort(403, 'User not found');
        }

        return $user;
    }

    /**
     * Parses the email address from client cert subject.
     *
     * @param string $subject
     * @return string
     */
    protected static function getEmailFromDn(string $subject): string
    {
        preg_match('/emailAddress=([\w\+]+@[a-z\-\d]+\.[a-z\-\.\d]{2,})/i', $subject, $match);

        /**
         * emailAddress must be set.
         * @see http://www.ietf.org/rfc/rfc2459.txt
         */
        if (empty($match) || count($match) < 2) {
            return abort(400, 'Missing or invalid emailAddress in subject certificate');
        }

        return $match[1];
    }

    private function checkRole(User $user, string $path): bool
    {
        $role = $user->role;
        if ($this->checkRegex($path, $this->publicPaths) || $this->checkRegex($path, $this->userPaths)) {
            return true;
        } else if ($this->checkRegex($path, $this->adminPaths)) {
            return $role == 'admin';
        } else if ($this->checkRegex($path, $this->sellerPaths)) {
            return $role == 'seller';
        } else if ($this->checkRegex($path, $this->customerPaths)) {
            return $role = 'customer';
        }

        return false;
    }

    private function checkRegex($path, $regexs)
    {
        foreach ($regexs as $regex) {
            if (preg_match($regex, $path)) {
                return true;
            }
        }

        return false;
    }
}
