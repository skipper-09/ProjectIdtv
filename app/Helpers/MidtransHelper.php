<?php

use App\Models\MidtransSetting;
function Midtrans($key){
    return MidtransSetting::first()->{$key};
}