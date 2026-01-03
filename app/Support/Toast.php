<?php

namespace App\Support;

class Toast
{
    /**
     * Flash a success toast message.
     */
    public static function success(string $message): void
    {
        session()->flash('success', $message);
    }

    /**
     * Flash an error toast message.
     */
    public static function error(string $message): void
    {
        session()->flash('error', $message);
    }

    /**
     * Flash an info toast message.
     */
    public static function info(string $message): void
    {
        session()->flash('info', $message);
    }
}
