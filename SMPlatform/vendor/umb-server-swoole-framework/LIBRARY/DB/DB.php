<?php declare( strict_types = 1 );
/**
 * Project: UmbServerSwooleFramework
 * File: DB.php
 * create: 2018/3/9
 * Author: Hugh.Lee
 * Email: umbrellahughlee@gmail.com
 * Copyright: Umbrella Inc.
 */

namespace UmbServer\SwooleFramework\LIBRARY\DB;

/**
 * Interface DB
 * @package UmbServer\SwooleLibrary\LIBRARY\DB
 */
interface DB
{
    function constructor();

    function connect();

    function disconnect();

    function isConnected(): bool;
}