<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class ApproveAllProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:approve-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'الموافقة على جميع المنتجات وتفعيلها';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('جاري الموافقة على جميع المنتجات...');
        
        $count = Product::where('is_approved', false)
            ->orWhere('is_active', false)
            ->update([
                'is_approved' => true,
                'is_active' => true,
            ]);

        $this->info("تم الموافقة على {$count} منتج بنجاح!");
        
        return Command::SUCCESS;
    }
}
