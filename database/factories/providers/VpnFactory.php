<?php

namespace Database\Factories\providers;

use App\Models\user\Vpn;
use Illuminate\Database\Eloquent\Factories\Factory;

class VpnFactory extends Factory
{

    protected $model = Vpn::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisVpn = [
            'OpenVPN',
            'IKEv2/IPSec',
            'L2TP/IPSec',
            'PPTP',
            'SSTP',
            'WireGuard',
            'SoftEther',
            'Cisco AnyConnect',
            'FortiClient',
            'GlobalProtect',
            'Pulse Secure',
            'CheckPoint Mobile',
            'Juniper SSL VPN',
            'SonicWall Mobile Connect',
            'OpenConnect',
            'StrongSwan',
            'Libreswan',
            'pfSense IPSec',
            'Mikrotik VPN',
            'Windows Built-in VPN',
            'Mac Built-in VPN',
            'Android VPN',
            'iOS VPN',
            'Shadowsocks',
            'V2Ray'
        ];

        return [
            'jenis_vpn' => $this->faker->randomElement($jenisVpn),
        ];
    }

    /**
     * Create a VPN with custom type
     */
    public function withType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $type,
        ]);
    }

    /**
     * Create OpenVPN protocol types
     */
    public function openVpn(): static
    {
        $openVpnTypes = [
            'OpenVPN UDP',
            'OpenVPN TCP',
            'OpenVPN SSL',
            'OpenVPN TLS'
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $this->faker->randomElement($openVpnTypes),
        ]);
    }

    /**
     * Create IPSec protocol types
     */
    public function ipSec(): static
    {
        $ipSecTypes = [
            'IKEv1/IPSec',
            'IKEv2/IPSec',
            'L2TP/IPSec',
            'IPSec Tunnel Mode',
            'IPSec Transport Mode'
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $this->faker->randomElement($ipSecTypes),
        ]);
    }

    /**
     * Create modern/secure VPN types
     */
    public function modern(): static
    {
        $modernTypes = [
            'WireGuard',
            'IKEv2/IPSec',
            'OpenVPN',
            'SoftEther'
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $this->faker->randomElement($modernTypes),
        ]);
    }

    /**
     * Create enterprise VPN types
     */
    public function enterprise(): static
    {
        $enterpriseTypes = [
            'Cisco AnyConnect',
            'FortiClient',
            'GlobalProtect',
            'Pulse Secure',
            'CheckPoint Mobile',
            'Juniper SSL VPN',
            'SonicWall Mobile Connect'
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $this->faker->randomElement($enterpriseTypes),
        ]);
    }

    /**
     * Create legacy/deprecated VPN types
     */
    public function legacy(): static
    {
        $legacyTypes = [
            'PPTP',
            'L2TP',
            'SSTP'
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $this->faker->randomElement($legacyTypes),
        ]);
    }

    /**
     * Create mobile platform VPN types
     */
    public function mobile(): static
    {
        $mobileTypes = [
            'Android VPN',
            'iOS VPN',
            'Windows Mobile VPN',
            'Mobile IKEv2',
            'Mobile OpenVPN'
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_vpn' => $this->faker->randomElement($mobileTypes),
        ]);
    }
}
