<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicContent extends Model
{
    use HasFactory;

    public function getDynamicContentByMenu($menu) {
        $getDynamicContent = $this->where('menu', $menu)->where('status','Active')->get();
        return $getDynamicContent;
    }

    public function getDynamicContentById($id) {
        $getDynamicContentInfo = $this->where('id', $id)->first();
        return $getDynamicContentInfo;
    }
}
