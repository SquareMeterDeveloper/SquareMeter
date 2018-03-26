<?php declare( strict_types = 1 );
/**
 * Project: UmbServerSwooleFramework
 * File: Client.php
 * Create: 2018/3/26
 * Author: Hugh.Lee
 * Email: umbrellahughlee@gmail.com
 * Copyright: Umbrella Inc.
 */

namespace UmbServer\SwooleFramework\COMPONENT\CLIENT;

/**
 * 客户端接口类
 * Interface Client
 * @package UmbServer\SwooleFramework\COMPONENT\CLIENT
 */
interface Client
{
    public
    function connect(): bool;

    public
    function disconnect(): bool;
}