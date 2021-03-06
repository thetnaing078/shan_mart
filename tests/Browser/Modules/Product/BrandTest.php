<?php

namespace Tests\Browser\Modules\Product;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Laravel\Dusk\Browser;
use Modules\Product\Entities\Brand;
use Tests\DuskTestCase;

class BrandTest extends DuskTestCase
{
    use WithFaker;


    public function setUp(): void
    {
        parent::setUp();


    }

    public function tearDown(): void
    {
        $brands = Brand::all();
        foreach($brands as $brand){
            if(File::exists(public_path($brand->logo))){
                File::delete(public_path($brand->logo));
            }
            $brand->delete();
        }

        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_for_visit_index_page(){
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/product/brands-list')
                    ->assertSee('Brand List');
        });
    }

    public function test_for_create_brand(){

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/product/brands-create')
                    ->assertSee('Add New Brand')
                    ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(2) > div > input', $this->faker->name)
                    ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(3) > div > div > div.note-editing-area > div.note-editable', $this->faker->paragraph)
                    ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(4) > div > input', $this->faker->url)
                    ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(6) > div > input',$this->faker->title)
                    ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(7) > div > textarea', $this->faker->name)
                    ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div:nth-child(2) > div > div')
                    ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div:nth-child(2) > div > div > ul > li:nth-child(1)')
                    ->attach('#logo',__DIR__.'/files/mi.png')
                    ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div:nth-child(7) > div > label > div')
                    ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div.col-12 > button')
                    ->assertPathIs('/product/brands-list')
                    ->waitFor('.toast-message',25)
                    ->assertSeeIn('.toast-message', 'Brand added Successfully!');
        });
    }

    public function test_for_edit_brand(){
        $this->test_for_create_brand();
        $this->browse(function (Browser $browser) {
            $brand = Brand::latest()->first();
            $browser->click('#tablecontents > tr:nth-child(1) > td:nth-child(6) > div > button')
                ->click('#tablecontents > tr:nth-child(1) > td:nth-child(6) > div > div > a.dropdown-item.edit_brand')
                ->assertPathIs('/product/brands-edit/'.$brand->id)
                ->assertSee('Edit Brand')
                ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(2) > div > input', '')
                ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div.col-12 > button')
                ->assertPathIs('/product/brands-edit/'.$brand->id)
                ->assertSee('The name field is required.')
                ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(2) > div > input', $this->faker->name)
                ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(3) > div > div > div.note-editing-area > div.note-editable', $this->faker->paragraph)
                ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(4) > div > input', $this->faker->url)
                ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(6) > div > input',$this->faker->title)
                ->type('#main-content > section > div > form > div > div.col-lg-8 > div > div > div:nth-child(7) > div > textarea', $this->faker->name)
                ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div:nth-child(2) > div > div')
                ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div:nth-child(2) > div > div > ul > li:nth-child(2)')
                ->attach('#logo',__DIR__.'/files/mi.png')
                ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div:nth-child(7) > div > label > div')
                ->click('#main-content > section > div > form > div > div.col-lg-4 > div > div > div.col-12 > button')
                ->assertPathIs('/product/brands-list')
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Brand updated Successfully!');
        });
    }

    public function test_for_delete_brand(){
        $this->test_for_create_brand();
        $this->browse(function (Browser $browser) {
            $browser->click('#tablecontents > tr:nth-child(1) > td:nth-child(6) > div > button')
                ->click('#tablecontents > tr:nth-child(1) > td:nth-child(6) > div > div > a.dropdown-item.delete_brand')
                ->whenAvailable('#confirm-delete > div > div > div.modal-body > div.mt-40.d-flex.justify-content-between', function($modal){
                    $modal->click('#delete_link')
                    ->assertPathIs('/product/brands-list');
                })
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Deleted successfully!');
        });
    }

    public function test_for_status_change(){
        $this->test_for_create_brand();
        $this->browse(function (Browser $browser) {
            $browser->click('#tablecontents > tr:nth-child(1) > td:nth-child(4) > label > div')
                ->pause(8000)
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Updated successfully!');
        });
    }

    public function test_for_is_feature_change(){
        $this->test_for_create_brand();
        $this->browse(function (Browser $browser) {
            $browser->click('#tablecontents > tr:nth-child(1) > td:nth-child(5) > label > div')
                ->pause(8000)
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Updated successfully!');
        });
    }

    public function test_for_bulk_upload(){
        $this->test_for_visit_index_page();
        $this->browse(function (Browser $browser) {
            $browser->click('#main-content > section > div > div > div.col-12 > div > div.main-title.d-md-flex > ul > li:nth-child(2) > a')
                ->assertPathIs('/product/bulk-brand-upload')
                ->assertSee('Bulk Brand Upload')
                ->click('#add_product > section > div > div > div > div > form > small > small > div > div > button')
                ->assertPathIs('/product/bulk-brand-upload')
                ->assertSee('The file field is required.')
                ->attach('#document_file_1', __DIR__.'/files/brand.xlsx')
                ->click('#add_product > section > div > div > div > div > form > small > small > div > div > button')
                ->assertPathIs('/product/bulk-brand-upload')
                ->waitFor('.toast-message',25)
                ->assertSeeIn('.toast-message', 'Successfully Uploaded !!!');
        });
        
    }
}
