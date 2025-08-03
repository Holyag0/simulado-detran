<?php

namespace App\Services;

use App\Models\Simulado;
use App\Models\Questao;
use Illuminate\Support\Collection;

class SimuladoService
{
    public function gerarQuestoesParaSimulado(Simulado $simulado): void
    {
        // Limpar questões existentes
        $simulado->questoes()->delete();
        
        // Gerar questões aleatórias por categoria
        $questoes = $simulado->gerarQuestoesAleatorias();
        
        // Associar ao simulado
        foreach ($questoes as $questao) {
            $simulado->questoes()->create([
                'categoria_id' => $questao->categoria_id,
                'pergunta' => $questao->pergunta,
                'alternativa_a' => $questao->alternativa_a,
                'alternativa_b' => $questao->alternativa_b,
                'alternativa_c' => $questao->alternativa_c,
                'alternativa_d' => $questao->alternativa_d,
                'resposta_correta' => $questao->resposta_correta,
                'explicacao' => $questao->explicacao,
                'ativo' => true,
            ]);
        }
    }
    
    public function validarConfiguracao(Simulado $simulado): array
    {
        $erros = [];
        $totalQuestoes = 0;
        
        foreach ($simulado->categorias as $categoria) {
            $quantidade = $categoria->pivot->quantidade_questoes;
            $questoesDisponiveis = $categoria->questoes()->where('ativo', true)->count();
            
            $totalQuestoes += $quantidade;
            
            if ($questoesDisponiveis < $quantidade) {
                $erros[] = "Categoria '{$categoria->nome}': solicitadas {$quantidade}, disponíveis {$questoesDisponiveis}";
            }
        }
        
        return [
            'valido' => empty($erros),
            'erros' => $erros,
            'total_questoes' => $totalQuestoes
        ];
    }
} 