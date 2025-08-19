<?php

namespace App\Services;

class PrizeService
{
    /**
     * Obter prêmios padrão do sistema
     *
     * @return array
     */
    public static function getDefaultPrizes()
    {
        // Tentar buscar prêmios configurados no banco de dados
        $configuredPrizes = \App\Models\Setting::getDefaultPrizes();
        
        if (!empty($configuredPrizes) && is_array($configuredPrizes)) {
            return $configuredPrizes;
        }

        // Se não há prêmios configurados, usar os padrão do sistema
        return [
            // Notas de dinheiro (mais comuns - 60% dos itens)
            [
                'name' => '1 REAL',
                'image' => '/assets/images/coin/1.png',
                'value' => 'R$ 1,00',
                'type' => 'money',
                'cash_value' => 1.00,
                'probability' => 30
            ],
            [
                'name' => '2 REAL',
                'image' => '/assets/images/coin/2.png',
                'value' => 'R$ 2,00',
                'type' => 'money',
                'cash_value' => 2.00,
                'probability' => 25
            ],
            [
                'name' => '3 REAL',
                'image' => '/assets/images/coin/3.png',
                'value' => 'R$ 3,00',
                'type' => 'money',
                'cash_value' => 3.00,
                'probability' => 20
            ],
            [
                'name' => '5 REAL',
                'image' => '/assets/images/coin/5.png',
                'value' => 'R$ 5,00',
                'type' => 'money',
                'cash_value' => 5.00,
                'probability' => 15
            ],
            [
                'name' => '10 REAL',
                'image' => '/assets/images/coin/10.png',
                'value' => 'R$ 10,00',
                'type' => 'money',
                'cash_value' => 10.00,
                'probability' => 10
            ],
            [
                'name' => '20 REAL',
                'image' => '/assets/images/coin/20.png',
                'value' => 'R$ 20,00',
                'type' => 'money',
                'cash_value' => 20.00,
                'probability' => 8
            ],
            [
                'name' => '50 REAL',
                'image' => '/assets/images/coin/50.png',
                'value' => 'R$ 50,00',
                'type' => 'money',
                'cash_value' => 50.00,
                'probability' => 5
            ],
            [
                'name' => '100 REAL',
                'image' => '/assets/images/coin/100.png',
                'value' => 'R$ 100,00',
                'type' => 'money',
                'cash_value' => 100.00,
                'probability' => 3
            ],
            // Produtos físicos (menos comuns - 40% dos itens)
            [
                'name' => 'iPhone 15 Pro',
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 8.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 1
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 4.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 1
            ],
            [
                'name' => 'PlayStation 5',
                'image' => 'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 3.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 1
            ],
            [
                'name' => 'MacBook Air',
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 9.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 0.5
            ],
            [
                'name' => 'Apple Watch',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 2.499',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 2
            ],
            [
                'name' => 'AirPods Pro',
                'image' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 1.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 3
            ],
            [
                'name' => 'Nintendo Switch',
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 2.299',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 2
            ],
            [
                'name' => 'Tablet Samsung',
                'image' => 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 1.499',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 3
            ]
        ];
    }

    /**
     * Obter prêmios padrão originais do sistema (sem considerar configurações)
     *
     * @return array
     */
    public static function getSystemDefaultPrizes()
    {
        return [
            // Notas de dinheiro (mais comuns - 60% dos itens)
            [
                'name' => '1 REAL',
                'image' => '/assets/images/coin/1.png',
                'value' => 'R$ 1,00',
                'type' => 'money',
                'cash_value' => 1.00,
                'probability' => 30
            ],
            [
                'name' => '2 REAL',
                'image' => '/assets/images/coin/2.png',
                'value' => 'R$ 2,00',
                'type' => 'money',
                'cash_value' => 2.00,
                'probability' => 25
            ],
            [
                'name' => '3 REAL',
                'image' => '/assets/images/coin/3.png',
                'value' => 'R$ 3,00',
                'type' => 'money',
                'cash_value' => 3.00,
                'probability' => 20
            ],
            [
                'name' => '5 REAL',
                'image' => '/assets/images/coin/5.png',
                'value' => 'R$ 5,00',
                'type' => 'money',
                'cash_value' => 5.00,
                'probability' => 15
            ],
            [
                'name' => '10 REAL',
                'image' => '/assets/images/coin/10.png',
                'value' => 'R$ 10,00',
                'type' => 'money',
                'cash_value' => 10.00,
                'probability' => 10
            ],
            [
                'name' => '20 REAL',
                'image' => '/assets/images/coin/20.png',
                'value' => 'R$ 20,00',
                'type' => 'money',
                'cash_value' => 20.00,
                'probability' => 8
            ],
            [
                'name' => '50 REAL',
                'image' => '/assets/images/coin/50.png',
                'value' => 'R$ 50,00',
                'type' => 'money',
                'cash_value' => 50.00,
                'probability' => 5
            ],
            [
                'name' => '100 REAL',
                'image' => '/assets/images/coin/100.png',
                'value' => 'R$ 100,00',
                'type' => 'money',
                'cash_value' => 100.00,
                'probability' => 3
            ],
            // Produtos físicos (menos comuns - 40% dos itens)
            [
                'name' => 'iPhone 15 Pro',
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 8.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 1
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 4.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 1
            ],
            [
                'name' => 'PlayStation 5',
                'image' => 'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 3.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 1
            ],
            [
                'name' => 'MacBook Air',
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 9.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 0.5
            ],
            [
                'name' => 'Apple Watch',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 2.499',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 2
            ],
            [
                'name' => 'AirPods Pro',
                'image' => 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 1.999',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 3
            ],
            [
                'name' => 'Nintendo Switch',
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 2.299',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 2
            ],
            [
                'name' => 'Tablet Samsung',
                'image' => 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=100&h=100&fit=crop&crop=center',
                'value' => 'R$ 1.499',
                'type' => 'product',
                'cash_value' => 0,
                'probability' => 3
            ]
        ];
    }

    public function getDemoPrizesWin()
    {
        return [
            [
                "name" => "R$ 10",
                "type" => "money",
                "value" => "R$ 10,00",
                "cash_value" => 10,
                "probability" => 15,
                "quantity" => 30,
                "image" => null,
                "description" => null,
                "isWinning" => true
            ]
        ];
    }

    public function getDemoPrizes()
    {
        return [
            [
                "name" => "R$ 10",
                "type" => "money",
                "value" => "R$ 10,00",
                "cash_value" => 10,
                "probability" => 15,
                "quantity" => 30,
                "image" => null,
                "description" => null,
                "isWinning" => true
            ],
            [
                "name" => "R$ 10",
                "type" => "money",
                "value" => "R$ 10,00",
                "cash_value" => 10,
                "probability" => 15,
                "quantity" => 30,
                "image" => null,
                "description" => null,
                "isWinning" => true
            ],
            [
                "name" => "R$ 10",
                "type" => "money",
                "value" => "R$ 10,00",
                "cash_value" => 10,
                "probability" => 15,
                "quantity" => 30,
                "image" => null,
                "description" => null,
                "isWinning" => true
            ],
            [
                "name" => "Nota R$ 3",
                "image" => "/assets/images/coin/3.png",
                "value" => "R$ 3,00",
                "type" => "money",
                "cash_value" => 3,
                "probability" => 20,
                "isWinning" => false
            ],
            [
                "name" => "R$ 1",
                "type" => "money",
                "value" => "R$ 1,00",
                "cash_value" => 1,
                "probability" => 30,
                "quantity" => 100,
                "image" => null,
                "description" => null,
                "isWinning" => false
            ],
            [
                "name" => "Nota R$ 5",
                "image" => "/assets/images/coin/5.png",
                "value" => "R$ 5,00",
                "type" => "money",
                "cash_value" => 5,
                "probability" => 15,
                "isWinning" => false
            ],
            [
                "name" => "R$ 1",
                "type" => "money",
                "value" => "R$ 1,00",
                "cash_value" => 1,
                "probability" => 30,
                "quantity" => 100,
                "image" => null,
                "description" => null,
                "isWinning" => false
            ],
            [
                "name" => "Nota R$ 3",
                "image" => "/assets/images/coin/3.png",
                "value" => "R$ 3,00",
                "type" => "money",
                "cash_value" => 3,
                "probability" => 20,
                "isWinning" => false
            ],
            [
                "name" => "Nota R$ 10",
                "image" => "/assets/images/coin/10.png",
                "value" => "R$ 10,00",
                "type" => "money",
                "cash_value" => 10,
                "probability" => 10,
                "isWinning" => false
            ]
        ];
    }

    /**
     * Completar lista de prêmios com prêmios padrão se necessário
     *
     * @param array $configuredPrizes Prêmios configurados no jogo
     * @param int $minRequired Mínimo de prêmios requeridos (default 9 para cartela)
     * @return array Lista completa de prêmios
     */
    public static function completePrizeList($configuredPrizes = [], $minRequired = 9)
    {
        $prizes = [];

        // Se há prêmios configurados, usar eles primeiro
        if (!empty($configuredPrizes) && is_array($configuredPrizes)) {
            $prizes = $configuredPrizes;
        }

        // Se não há prêmios suficientes, completar com prêmios padrão
        if (count($prizes) < $minRequired) {
            $defaultPrizes = self::getSystemDefaultPrizes();

            // Obter nomes dos prêmios já configurados para evitar duplicatas
            $configuredNames = array_column($prizes, 'name');

            // Adicionar prêmios padrão que não conflitam
            foreach ($defaultPrizes as $defaultPrize) {
                if (!in_array($defaultPrize['name'], $configuredNames)) {
                    $prizes[] = $defaultPrize;

                    // Parar quando atingir o mínimo requerido
                    if (count($prizes) >= $minRequired) {
                        break;
                    }
                }
            }

            // Se ainda não tem o suficiente (caso extremo), usar todos os padrão
            if (count($prizes) < $minRequired) {
                $prizes = array_merge($prizes, $defaultPrizes);
                $prizes = array_slice($prizes, 0, max($minRequired, count($defaultPrizes)));
            }
        }

        return $prizes;
    }

    /**
     * Obter prêmios para exibição na listagem (sempre mostra variedade)
     *
     * @param array $configuredPrizes Prêmios configurados no jogo
     * @return array Lista de prêmios para exibição
     */
    public static function getDisplayPrizes($configuredPrizes = [])
    {
        // Para listagem, sempre mostrar pelo menos 8 prêmios diferentes
        $prizes = self::completePrizeList($configuredPrizes, 8);

        // Se há muitos prêmios configurados, mostrar os mais importantes
        if (count($prizes) > 12) {
            // Priorizar prêmios com maior probabilidade ou valor
            usort($prizes, function ($a, $b) {
                $aPriority = ($a['probability'] ?? 1) * (($a['cash_value'] ?? 0) + 1);
                $bPriority = ($b['probability'] ?? 1) * (($b['cash_value'] ?? 0) + 1);
                return $bPriority <=> $aPriority;
            });

            $prizes = array_slice($prizes, 0, 12);
        }

        array_walk($prizes, function (&$prize) {
            if (isset($prize['image']) && !empty($prize['image'])) {
                // Se a imagem já começa com /storage/ ou /assets/ ou http, não modificar
                if (strpos($prize['image'], '/storage/') === 0 || 
                    strpos($prize['image'], '/assets/') === 0 || 
                    strpos($prize['image'], 'http') === 0) {
                    // Não modificar
                    return;
                }
                
                // Se não, adicionar /storage/ na frente
                $prize['image'] = "/storage/" . ltrim($prize['image'], '/');
            }
        });

        return $prizes;
    }

    /**
     * Obter prêmios para geração da cartela (com lógica de distribuição)
     *
     * @param array $configuredPrizes Prêmios configurados no jogo
     * @return array Lista de prêmios para cartela
     */
    public static function getCardPrizes($configuredPrizes = [])
    {
        // Para cartela, garantir pelo menos 9 prêmios
        return self::completePrizeList($configuredPrizes, 9);
    }

    /**
     * Separar prêmios por tipo
     *
     * @param array $prizes Lista de prêmios
     * @return array Array com chaves 'money' e 'product'
     */
    public static function separatePrizesByType($prizes)
    {
        $moneyPrizes = array_filter($prizes, function ($prize) {
            return isset($prize['type']) && $prize['type'] === 'money';
        });

        $productPrizes = array_filter($prizes, function ($prize) {
            return isset($prize['type']) && $prize['type'] === 'product';
        });

        return [
            'money' => array_values($moneyPrizes),
            'product' => array_values($productPrizes)
        ];
    }
}
