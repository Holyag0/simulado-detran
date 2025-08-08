<?php

namespace App\Livewire;

use App\Models\Banner;
use Livewire\Component;

class BannerCarrossel extends Component
{
    public $banners = [];

    public function mount()
    {
        $this->carregarBanners();
    }

    public function carregarBanners()
    {
        $this->banners = Banner::ativos()->get();
    }

    public function render()
    {
        return view('livewire.banner-carrossel', [
            'banners' => $this->banners
        ]);
    }
}
