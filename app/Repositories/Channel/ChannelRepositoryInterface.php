<?php
namespace App\Repositories\Channel;

interface ChannelRepositoryInterface
{
    public function changeStatus($id);

    public function getChannels($attribute = []);

    public function getChannelTypes();
}
