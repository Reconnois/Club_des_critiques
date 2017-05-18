<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatbox extends Model
{
    protected $table = 'chatbox';

    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';

    /**
     * Define one-to-one relation between chatbox and its user sender
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'id_user_sender');
    }

    /**
     * Define one-to-one relation between chatbox and room
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }
}
