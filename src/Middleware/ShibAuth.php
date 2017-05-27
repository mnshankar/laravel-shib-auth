<?php namespace mnshankar\Shib\Middleware;

use Closure;

class ShibAuth
{
    private $shibValues;
    private $tokenUserMap;
    private $uniqueField;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::guest()) {
            $this->tokenUserMap = \Config::get('shib.token_map');
            $this->uniqueField = \Config::get('shib.unique_field');            
            $this->shibValues = $this->getShibTokenValues(array_keys($this->tokenUserMap));        
            $this->logIn();
        }
        return $next($request);
    }

    /**
     * Either var or REDIRECT_var must get set by shib
     * If neither are set, throw an exception
     * @param $tokens
     * @return array
     * @throws \Exception
     */
    protected function getShibTokenValues($tokens)
    {
        $shibValues = [];
        foreach ($tokens as $token) {            
            $shibValues[$token] = (\Request::server($token) == '') ?
                \Request::server('REDIRECT_' . $token) :
                \Request::server($token);
            if ($shibValues[$token] == '') {
                throw new \Exception("Shibboleth authentication unsuccessful. Sorry!");
            }
        }
        return $shibValues;
    }

    protected function logIn()
    {
        $authModel = \Config::get('auth.model');
        $userTable = new $authModel;
        //if user is not in user table, add
        $user = $userTable->firstOrNew([$this->uniqueField => $this->shibValues[$this->uniqueField]]);
        //create user if not exists in table
        if (!$user->exists) {
            foreach ($this->tokenUserMap as $token=>$dbField) {
                $user->$dbField = $this->shibValues[$token];
           }
           $user->save();
        }
        //log-in the user using Laravel Auth
        \Auth::login($user);
    }

}
