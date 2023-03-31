<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_profile_information_is_available()
    {
        $this->actingAs($user = User::factory()->create());

        $component = Livewire::test(UpdateProfileInformationForm::class);

        $this->assertEquals($user->name, $component->state['name']);
        $this->assertEquals($user->email, $component->state['email']);
    }

    public function test_profile_information_can_be_updated()
    {
        $this->actingAs($user = User::factory()->create());

        Livewire::test(UpdateProfileInformationForm::class)
                ->set('state', [
                    'name' => 'Test Name', 
                    'email' => 'test@example.com',
                    'phone' => '3480000000',
                    'vat' => '00000000000',
                    'fiscal_code' => 'FCFCFCFCFCFCFCFC'
                ])
                ->call('updateProfileInformation');

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
        $this->assertEquals('3480000000', $user->fresh()->phone);
        $this->assertEquals('00000000000', $user->fresh()->vat);
        $this->assertEquals('FCFCFCFCFCFCFCFC', $user->fresh()->fiscal_code);
    }
}
