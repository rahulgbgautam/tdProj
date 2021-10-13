<?php
/**
 * This file is part of App\Lib\UserVerification package.
 *
 */
namespace App\Lib;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Schema\Builder;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserVerification
{
	/**
     * Schema builder instance.
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    private static $schema;
    /**
     * Create a new instance.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @param  \Illuminate\Database\Schema\Builder  $schema
     * @return void
     */
    public function __construct(Builder $schema)
    {
        $this->schema = $schema;
    }
	/**
     * Generate and save a verification token for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return bool
     */
    public static function generate($user)
    {
        if (empty($user->email)) {
            
        }
        return self::saveToken($user, self::generateToken());
    }
	 /**
     * Generate the verification token.
     *
     * @return string|bool
     */
    static protected function generateToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

     /**
     * Update and save the model instance with the verification token.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return bool
     *
     * @throws \Jrean\UserVerification\Exceptions\ModelNotCompliantException
     */
    static protected function saveToken($user, $token)
    {
        // if (! $this->isCompliant($user)) {
            
        // }
        $user->remember_token = $token;
        return $user->save();
    }

    /**
     * Determine if the given model table has the verified and verification_token
     * columns.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return  bool
     */
    static protected function isCompliant($user)
    {
        return $this->hasColumn($user, 'remember_token')
            ? true
            : false;
    }
    /**
     * Check if the given model talbe has the given column.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $column
     * @return bool
     */
    static protected function hasColumn($user, $column)
    {
        return static::$schema->hasColumn($user->getTable(), $column);
    }
}