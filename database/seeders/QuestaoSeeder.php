<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Questao;
use Illuminate\Database\Seeder;

class QuestaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Categoria::all();
        
        // Questões de Mecânica Básica
        $mecanica = $categorias->where('nome', 'Mecânica Básica')->first();
        if ($mecanica) {
            $questoesMecanica = [
                [
                    'pergunta' => 'Qual é a função principal do óleo do motor?',
                    'alternativa_a' => 'Lubrificar as peças móveis',
                    'alternativa_b' => 'Refrigerar o motor',
                    'alternativa_c' => 'Limpar o combustível',
                    'alternativa_d' => 'Aumentar a potência',
                    'resposta_correta' => 'a',
                    'explicacao' => 'O óleo do motor tem como função principal lubrificar as peças móveis, reduzindo o atrito e o desgaste.',
                ],
                [
                    'pergunta' => 'O que indica a luz do óleo no painel?',
                    'alternativa_a' => 'Nível de combustível baixo',
                    'alternativa_b' => 'Pressão do óleo baixa',
                    'alternativa_c' => 'Temperatura alta',
                    'alternativa_d' => 'Bateria descarregada',
                    'resposta_correta' => 'b',
                    'explicacao' => 'A luz do óleo indica pressão baixa do óleo do motor, que pode causar danos graves.',
                ],
                [
                    'pergunta' => 'Qual é a função do radiador?',
                    'alternativa_a' => 'Aquecer o motor',
                    'alternativa_b' => 'Refrigerar o motor',
                    'alternativa_c' => 'Lubrificar as peças',
                    'alternativa_d' => 'Filtrar o ar',
                    'resposta_correta' => 'b',
                    'explicacao' => 'O radiador é responsável por refrigerar o motor, mantendo a temperatura ideal.',
                ],
            ];
            
            foreach ($questoesMecanica as $questao) {
                Questao::create(array_merge($questao, [
                    'categoria_id' => $mecanica->id,
                ]));
            }
        }
        
        // Questões de Legislação
        $legislacao = $categorias->where('nome', 'Legislação')->first();
        if ($legislacao) {
            $questoesLegislacao = [
                [
                    'pergunta' => 'Qual a velocidade máxima em vias urbanas?',
                    'alternativa_a' => '60 km/h',
                    'alternativa_b' => '80 km/h',
                    'alternativa_c' => '100 km/h',
                    'alternativa_d' => '120 km/h',
                    'resposta_correta' => 'a',
                    'explicacao' => 'Em vias urbanas, a velocidade máxima é de 60 km/h, salvo indicação em contrário.',
                ],
                [
                    'pergunta' => 'É permitido dirigir sem CNH?',
                    'alternativa_a' => 'Sim, em emergências',
                    'alternativa_b' => 'Não, nunca',
                    'alternativa_c' => 'Sim, em vias rurais',
                    'alternativa_d' => 'Sim, aos domingos',
                    'resposta_correta' => 'b',
                    'explicacao' => 'É proibido dirigir sem CNH válida, exceto em casos específicos de aprendizagem.',
                ],
                [
                    'pergunta' => 'Qual a penalidade para dirigir embriagado?',
                    'alternativa_a' => 'Multa apenas',
                    'alternativa_b' => 'Suspensão da CNH',
                    'alternativa_c' => 'Cassação da CNH',
                    'alternativa_d' => 'Nenhuma penalidade',
                    'resposta_correta' => 'c',
                    'explicacao' => 'Dirigir embriagado resulta na cassação da CNH e outras penalidades.',
                ],
            ];
            
            foreach ($questoesLegislacao as $questao) {
                Questao::create(array_merge($questao, [
                    'categoria_id' => $legislacao->id,
                ]));
            }
        }
        
        // Questões de Meio Ambiente
        $meioAmbiente = $categorias->where('nome', 'Meio Ambiente')->first();
        if ($meioAmbiente) {
            $questoesMeioAmbiente = [
                [
                    'pergunta' => 'Qual a melhor forma de reduzir a poluição?',
                    'alternativa_a' => 'Usar mais combustível',
                    'alternativa_b' => 'Manter o motor regulado',
                    'alternativa_c' => 'Dirigir mais rápido',
                    'alternativa_d' => 'Ignorar a manutenção',
                    'resposta_correta' => 'b',
                    'explicacao' => 'Manter o motor regulado reduz a emissão de poluentes.',
                ],
                [
                    'pergunta' => 'O que fazer com o óleo usado?',
                    'alternativa_a' => 'Descartar no lixo comum',
                    'alternativa_b' => 'Jogar no esgoto',
                    'alternativa_c' => 'Entregar em posto de coleta',
                    'alternativa_d' => 'Queimar',
                    'resposta_correta' => 'c',
                    'explicacao' => 'O óleo usado deve ser entregue em postos de coleta para reciclagem.',
                ],
            ];
            
            foreach ($questoesMeioAmbiente as $questao) {
                Questao::create(array_merge($questao, [
                    'categoria_id' => $meioAmbiente->id,
                ]));
            }
        }
        
        // Questões de Direção Defensiva
        $direcaoDefensiva = $categorias->where('nome', 'Direção Defensiva')->first();
        if ($direcaoDefensiva) {
            $questoesDirecaoDefensiva = [
                [
                    'pergunta' => 'Qual a distância segura entre veículos?',
                    'alternativa_a' => '2 segundos',
                    'alternativa_b' => '5 segundos',
                    'alternativa_c' => '10 segundos',
                    'alternativa_d' => 'Não importa',
                    'resposta_correta' => 'a',
                    'explicacao' => 'A distância segura é de pelo menos 2 segundos entre veículos.',
                ],
                [
                    'pergunta' => 'O que fazer em caso de aquaplanagem?',
                    'alternativa_a' => 'Frear bruscamente',
                    'alternativa_b' => 'Acelerar',
                    'alternativa_c' => 'Reduzir velocidade gradualmente',
                    'alternativa_d' => 'Virar o volante',
                    'resposta_correta' => 'c',
                    'explicacao' => 'Em caso de aquaplanagem, reduza a velocidade gradualmente sem frear bruscamente.',
                ],
            ];
            
            foreach ($questoesDirecaoDefensiva as $questao) {
                Questao::create(array_merge($questao, [
                    'categoria_id' => $direcaoDefensiva->id,
                ]));
            }
        }
        
        // Questões de Sinalização
        $sinalizacao = $categorias->where('nome', 'Sinalização')->first();
        if ($sinalizacao) {
            $questoesSinalizacao = [
                [
                    'pergunta' => 'O que significa placa vermelha com "PARE"?',
                    'alternativa_a' => 'Reduzir velocidade',
                    'alternativa_b' => 'Parar obrigatoriamente',
                    'alternativa_c' => 'Atenção',
                    'alternativa_d' => 'Proibido parar',
                    'resposta_correta' => 'b',
                    'explicacao' => 'A placa "PARE" indica parada obrigatória.',
                ],
                [
                    'pergunta' => 'Qual a cor da placa de "DÊ A PREFERÊNCIA"?',
                    'alternativa_a' => 'Vermelha',
                    'alternativa_b' => 'Amarela',
                    'alternativa_c' => 'Azul',
                    'alternativa_d' => 'Verde',
                    'resposta_correta' => 'b',
                    'explicacao' => 'A placa "DÊ A PREFERÊNCIA" é amarela.',
                ],
            ];
            
            foreach ($questoesSinalizacao as $questao) {
                Questao::create(array_merge($questao, [
                    'categoria_id' => $sinalizacao->id,
                ]));
            }
        }
        
        // Questões de Primeiros Socorros
        $primeirosSocorros = $categorias->where('nome', 'Primeiros Socorros')->first();
        if ($primeirosSocorros) {
            $questoesPrimeirosSocorros = [
                [
                    'pergunta' => 'O que fazer em caso de acidente?',
                    'alternativa_a' => 'Fugir do local',
                    'alternativa_b' => 'Prestar socorro',
                    'alternativa_c' => 'Ignorar',
                    'alternativa_d' => 'Fotografar',
                    'resposta_correta' => 'b',
                    'explicacao' => 'Em caso de acidente, é obrigatório prestar socorro às vítimas.',
                ],
                [
                    'pergunta' => 'Qual o número de emergência?',
                    'alternativa_a' => '190',
                    'alternativa_b' => '192',
                    'alternativa_c' => '193',
                    'alternativa_d' => '194',
                    'resposta_correta' => 'c',
                    'explicacao' => 'O número 193 é do Corpo de Bombeiros para emergências.',
                ],
            ];
            
            foreach ($questoesPrimeirosSocorros as $questao) {
                Questao::create(array_merge($questao, [
                    'categoria_id' => $primeirosSocorros->id,
                ]));
            }
        }
    }
}
