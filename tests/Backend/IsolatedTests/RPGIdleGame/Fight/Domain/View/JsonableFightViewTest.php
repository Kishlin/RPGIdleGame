<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\View;

use JsonException;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightParticipantView
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightTurnView
 * @covers \Kishlin\Backend\RPGIdleGame\Fight\Domain\View\JsonableFightView
 */
final class JsonableFightViewTest extends TestCase
{
    public function testItCanBeCreatedFromSource(): void
    {
        $source = $this->source();

        $view = JsonableFightView::fromSource($source);

        self::assertInstanceOf(JsonableFightView::class, $view);
    }

    /**
     * @depends testItCanBeCreatedFromSource
     *
     * @throws JsonException
     */
    public function testItCanBeConvertedToJson(): void
    {
        $view = JsonableFightView::fromSource($this->source());

        self::assertSame(self::json(), $view->toJson());
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

    private static function json(): string
    {
        return <<<'JSON'
{"id":"0efbc2b9-9244-40ab-b2cd-c535af98bcb6","initiator":{"account_username":"Stranger","character_name":"Fighter","health":30,"attack":25,"defense":5,"magik":10,"rank":31},"opponent":{"account_username":"User","character_name":"Kishlin","health":25,"attack":18,"defense":12,"magik":10,"rank":26},"turns":[{"character_name":"Fighter","index":0,"attacker_attack":16,"attacker_magik":12,"attacker_dice_roll":16,"defender_defense":12,"damage_dealt":4,"defender_health":21},{"character_name":"Kishlin","index":1,"attacker_attack":18,"attacker_magik":10,"attacker_dice_roll":2,"defender_defense":8,"damage_dealt":0,"defender_health":20},{"character_name":"Fighter","index":2,"attacker_attack":16,"attacker_magik":12,"attacker_dice_roll":12,"defender_defense":12,"damage_dealt":12,"defender_health":9},{"character_name":"Kishlin","index":3,"attacker_attack":18,"attacker_magik":10,"attacker_dice_roll":16,"defender_defense":8,"damage_dealt":8,"defender_health":12},{"character_name":"Fighter","index":4,"attacker_attack":16,"attacker_magik":12,"attacker_dice_roll":15,"defender_defense":12,"damage_dealt":3,"defender_health":6},{"character_name":"Kishlin","index":5,"attacker_attack":18,"attacker_magik":10,"attacker_dice_roll":7,"defender_defense":8,"damage_dealt":0,"defender_health":12},{"character_name":"Fighter","index":6,"attacker_attack":16,"attacker_magik":12,"attacker_dice_roll":12,"defender_defense":12,"damage_dealt":12,"defender_health":0}],"winner_id":"938a453c-af06-46a1-89d8-52acdd564b48"}
JSON;
    }
}
