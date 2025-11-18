<?php

if (!function_exists('traduzirStatus')) {
    /**
     * Traduz o status do ticket para português
     */
    function traduzirStatus(string $status): string
    {
        $traducoes = [
            'novo' => 'Novo',
            'aberto' => 'Aberto',
            'em_progresso' => 'Em Progresso',
            'pendente' => 'Pendente',
            'resolvido' => 'Resolvido',
            'fechado' => 'Fechado',
        ];

        return $traducoes[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}

if (!function_exists('corStatus')) {
    /**
     * Retorna a cor apropriada para cada status
     */
    function corStatus(string $status): array
    {
        $cores = [
            'novo' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            'aberto' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
            'em_progresso' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            'pendente' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
            'resolvido' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
            'fechado' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
        ];

        return $cores[$status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
    }
}
