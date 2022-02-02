<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\View;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightView;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightParticipantView
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightTurnView
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightView
 */
final class SerializableFightViewTest extends TestCase
{
    public function testItCanBeCreatedFromSource(): void
    {
        $source = $this->source();

        $view = SerializableFightView::fromSource($source);

        self::assertInstanceOf(SerializableFightView::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     */
    public function testItCanBeSerialized(): void
    {
        $view = SerializableFightView::fromSource($this->source());

        self::assertSame(self::sourceSerialized(), serialize($view));
    }

    public function testItCanBeUnserialized(): void
    {
        self::assertInstanceOf(SerializableFightView::class, unserialize($this->sourceSerialized()));
    }

    /**
     * @return array{id: string, initiator: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, opponent: array{account_username: string, character_name: string, health: int, attack: int, defense: int, magik: int, rank: int}, turns: array<array{character_name: string, index: int, attacker_attack: int, attacker_magik: int, attacker_dice_roll: int, defender_defense: int, damage_dealt: int, defender_health: int}>, winner_id: ?string}
     */
    private function source(): array
    {
        return [
            'id'        => '0efbc2b9-9244-40ab-b2cd-c535af98bcb6',
            'initiator' => [
                'account_username' => 'Stranger',
                'character_name'   => 'Fighter',
                'health'           => 30,
                'attack'           => 25,
                'defense'          => 5,
                'magik'            => 10,
                'rank'             => 31,
            ],
            'opponent' => [
                'account_username' => 'User',
                'character_name'   => 'Kishlin',
                'health'           => 25,
                'attack'           => 18,
                'defense'          => 12,
                'magik'            => 10,
                'rank'             => 26,
            ],
            'turns' => [
                [
                    'character_name'     => 'Fighter',
                    'index'              => 0,
                    'attacker_attack'    => 16,
                    'attacker_magik'     => 12,
                    'attacker_dice_roll' => 16,
                    'defender_defense'   => 12,
                    'damage_dealt'       => 4,
                    'defender_health'    => 21,
                ], [
                    'character_name'     => 'Kishlin',
                    'index'              => 1,
                    'attacker_attack'    => 18,
                    'attacker_magik'     => 10,
                    'attacker_dice_roll' => 2,
                    'defender_defense'   => 8,
                    'damage_dealt'       => 0,
                    'defender_health'    => 20,
                ], [
                    'character_name'     => 'Fighter',
                    'index'              => 2,
                    'attacker_attack'    => 16,
                    'attacker_magik'     => 12,
                    'attacker_dice_roll' => 12,
                    'defender_defense'   => 12,
                    'damage_dealt'       => 12,
                    'defender_health'    => 9,
                ], [
                    'character_name'     => 'Kishlin',
                    'index'              => 3,
                    'attacker_attack'    => 18,
                    'attacker_magik'     => 10,
                    'attacker_dice_roll' => 16,
                    'defender_defense'   => 8,
                    'damage_dealt'       => 8,
                    'defender_health'    => 12,
                ], [
                    'character_name'     => 'Fighter',
                    'index'              => 4,
                    'attacker_attack'    => 16,
                    'attacker_magik'     => 12,
                    'attacker_dice_roll' => 15,
                    'defender_defense'   => 12,
                    'damage_dealt'       => 3,
                    'defender_health'    => 6,
                ], [
                    'character_name'     => 'Kishlin',
                    'index'              => 5,
                    'attacker_attack'    => 18,
                    'attacker_magik'     => 10,
                    'attacker_dice_roll' => 7,
                    'defender_defense'   => 8,
                    'damage_dealt'       => 0,
                    'defender_health'    => 12,
                ], [
                    'character_name'     => 'Fighter',
                    'index'              => 6,
                    'attacker_attack'    => 16,
                    'attacker_magik'     => 12,
                    'attacker_dice_roll' => 12,
                    'defender_defense'   => 12,
                    'damage_dealt'       => 12,
                    'defender_health'    => 0,
                ],
            ],
            'winner_id' => '938a453c-af06-46a1-89d8-52acdd564b48',
        ];
    }

    private static function sourceSerialized(): string
    {
        return <<<'TXT'
O:67:"Kishlin\Backend\RPGIdleGame\Fight\Domain\View\SerializableFightView":5:{s:2:"id";s:36:"0efbc2b9-9244-40ab-b2cd-c535af98bcb6";s:9:"initiator";a:7:{s:16:"account_username";s:8:"Stranger";s:14:"character_name";s:7:"Fighter";s:6:"health";i:30;s:6:"attack";i:25;s:7:"defense";i:5;s:5:"magik";i:10;s:4:"rank";i:31;}s:8:"opponent";a:7:{s:16:"account_username";s:4:"User";s:14:"character_name";s:7:"Kishlin";s:6:"health";i:25;s:6:"attack";i:18;s:7:"defense";i:12;s:5:"magik";i:10;s:4:"rank";i:26;}s:5:"turns";a:7:{i:0;a:8:{s:14:"character_name";s:7:"Fighter";s:5:"index";i:0;s:15:"attacker_attack";i:16;s:14:"attacker_magik";i:12;s:18:"attacker_dice_roll";i:16;s:16:"defender_defense";i:12;s:12:"damage_dealt";i:4;s:15:"defender_health";i:21;}i:1;a:8:{s:14:"character_name";s:7:"Kishlin";s:5:"index";i:1;s:15:"attacker_attack";i:18;s:14:"attacker_magik";i:10;s:18:"attacker_dice_roll";i:2;s:16:"defender_defense";i:8;s:12:"damage_dealt";i:0;s:15:"defender_health";i:20;}i:2;a:8:{s:14:"character_name";s:7:"Fighter";s:5:"index";i:2;s:15:"attacker_attack";i:16;s:14:"attacker_magik";i:12;s:18:"attacker_dice_roll";i:12;s:16:"defender_defense";i:12;s:12:"damage_dealt";i:12;s:15:"defender_health";i:9;}i:3;a:8:{s:14:"character_name";s:7:"Kishlin";s:5:"index";i:3;s:15:"attacker_attack";i:18;s:14:"attacker_magik";i:10;s:18:"attacker_dice_roll";i:16;s:16:"defender_defense";i:8;s:12:"damage_dealt";i:8;s:15:"defender_health";i:12;}i:4;a:8:{s:14:"character_name";s:7:"Fighter";s:5:"index";i:4;s:15:"attacker_attack";i:16;s:14:"attacker_magik";i:12;s:18:"attacker_dice_roll";i:15;s:16:"defender_defense";i:12;s:12:"damage_dealt";i:3;s:15:"defender_health";i:6;}i:5;a:8:{s:14:"character_name";s:7:"Kishlin";s:5:"index";i:5;s:15:"attacker_attack";i:18;s:14:"attacker_magik";i:10;s:18:"attacker_dice_roll";i:7;s:16:"defender_defense";i:8;s:12:"damage_dealt";i:0;s:15:"defender_health";i:12;}i:6;a:8:{s:14:"character_name";s:7:"Fighter";s:5:"index";i:6;s:15:"attacker_attack";i:16;s:14:"attacker_magik";i:12;s:18:"attacker_dice_roll";i:12;s:16:"defender_defense";i:12;s:12:"damage_dealt";i:12;s:15:"defender_health";i:0;}}s:9:"winner_id";s:36:"938a453c-af06-46a1-89d8-52acdd564b48";}
TXT;
    }
}
