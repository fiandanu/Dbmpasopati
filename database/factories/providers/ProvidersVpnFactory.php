<?php

namespace Database\Factories\providers;

use App\Models\user\provider\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvidersVpnFactory extends Factory
{

    protected $model = Provider::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $providers = [
            'ExpressVPN',
            'NordVPN',
            'CyberGhost',
            'Surfshark',
            'ProtonVPN',
            'IPVanish',
            'Private Internet Access',
            'Hotspot Shield',
            'TunnelBear',
            'VyprVPN',
            'StrongVPN',
            'PureVPN',
            'Windscribe',
            'Atlas VPN',
            'Mullvad',
            'FastestVPN',
            'Trust.Zone',
            'SaferVPN',
            'ZenMate',
            'HideMyAss',
            'Ivacy',
            'PrivateVPN',
            'VPN Unlimited',
            'SecureVPN',
            'BullGuard VPN'
        ];

        return [
            'nama_provider' => $this->faker->randomElement($providers),
        ];
    }

    /**
     * Create a provider with custom name
     */
    public function withName(string $name): static
    {
        return $this->state(fn(array $attributes) => [
            'nama_provider' => $name,
        ]);
    }

    /**
     * Create premium VPN providers
     */
    public function premium(): static
    {
        $premiumProviders = [
            'ExpressVPN',
            'NordVPN',
            'CyberGhost',
            'Surfshark',
            'ProtonVPN'
        ];

        return $this->state(fn(array $attributes) => [
            'nama_provider' => $this->faker->randomElement($premiumProviders),
        ]);
    }

    /**
     * Create free VPN providers
     */
    public function free(): static
    {
        $freeProviders = [
            'ProtonVPN Free',
            'Windscribe Free',
            'TunnelBear Free',
            'Hotspot Shield Free',
            'Atlas VPN Free'
        ];

        return $this->state(fn(array $attributes) => [
            'nama_provider' => $this->faker->randomElement($freeProviders),
        ]);
    }
}
