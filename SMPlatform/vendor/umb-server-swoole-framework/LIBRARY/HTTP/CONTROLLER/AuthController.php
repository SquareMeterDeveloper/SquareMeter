<?php declare( strict_types = 1 );
/**
 * Project: UmbServerSwooleFramework
 * File: AuthController.php
 * Create: 2018/3/20
 * Author: Hugh.Lee
 * Email: umbrellahughlee@gmail.com
 * Copyright: Umbrella Inc.
 */

namespace UmbServer\SwooleFramework\LIBRARY\HTTP\CONTROLLER;

use UmbServer\SwooleFramework\LIBRARY\AUTH\AuthUser;
use UmbServer\SwooleFramework\LIBRARY\ENUM\_HttpRequestVerb;
use UmbServer\SwooleFramework\LIBRARY\ERROR\HttpError;
use UmbServer\SwooleFramework\LIBRARY\HTTP\REQUEST\ApiTarget;
use UmbServer\SwooleFramework\LIBRARY\UTIL\Algorithm;
use UmbServer\SwooleFramework\LIBRARY\UTIL\Console;
use UmbServer\SwooleFramework\LIBRARY\UTIL\Serialize;

/**
 * 认证控制器类
 * Class AuthController
 * @package UmbServer\SwooleFramework\LIBRARY\HTTP\CONTROLLER
 */
class AuthController extends Controller
{
    private $_auth_user; //登陆用户实例
    private $_auth_user_class;

    public $request_uri;
    public $api_key;
    public $signature;

    public
    function __construct( ApiTarget $api_target )
    {
        parent::__construct( $api_target );
        $this->request_uri = $api_target->request_uri;
        $this->api_key     = $api_target->api_key;
        $this->signature   = $api_target->signature;
    }

    /**
     * 设置验证用户类名
     * @param string $auth_user_class
     */
    public
    function setAuthUserClass( string $auth_user_class )
    {
        $this->_auth_user_class = $auth_user_class;
    }

    /**
     * 获取验证用户类名
     * @return string
     */
    private
    function getAuthUserClass(): string
    {
        return $this->_auth_user_class;
    }

    /**
     * 设置验证用户
     * @param $auth_user
     */
    private
    function setAuthUser( $auth_user )
    {
        $this->_auth_user = $auth_user;
    }

    /**
     * 获取验证用户
     * @return AuthUser
     */
    private
    function getAuthUser(): AuthUser
    {
        return $this->_auth_user;
    }

    /**
     * 重写前置方法用于验证
     * @return bool
     * @throws HttpError
     */
    public
    function _before(): bool
    {
        $auth_user = call_user_func( [ $this->getAuthUserClass(), 'getByApiKey' ], $this->api_key );
        $this->setAuthUser( $auth_user );

        //如果是上传文件则不验证signature
        if ( $this->VERB === _HttpRequestVerb::UPLOAD_FILE ) {
            return true;
        }

        //检查signature
        $api_secret       = $this->getAuthUser()->api_secret;
        $payload          = $this->VERB . $this->request_uri . Serialize::encode( $this->PARAMS );
        $expire_signature = Algorithm::hmacSha256( $payload, $api_secret );

        Console::log( 'Expire Preload String: ' . $payload );
        Console::log( 'Expire Signature: ' . $expire_signature );
        Console::log( 'Signature: ' . $this->signature );
        if ( $this->signature !== $expire_signature ) {
            throw new HttpError( HttpError::API_AUTH_FAILED );
        }
        return true;
    }

    public
    function _after(): bool
    {
        parent::_after(); // TODO: Change the autogenerated stub
    }
}