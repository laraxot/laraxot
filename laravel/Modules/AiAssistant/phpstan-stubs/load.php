<?php

declare(strict_types=1);

/**
 * PHPStan stubs for inspector-apm/neuron-ai (optional runtime dependency).
 * Loaded only during static analysis via phpstan_bootstrap.php.
 */
$stubFiles = [
    __DIR__.'/NeuronAI/Providers/AIProviderInterface.php',
    __DIR__.'/NeuronAI/Providers/OpenAI/OpenAI.php',
    __DIR__.'/NeuronAI/Providers/OpenAILike.php',
    __DIR__.'/NeuronAI/Tools/PropertyType.php',
    __DIR__.'/NeuronAI/Tools/ToolInterface.php',
    __DIR__.'/NeuronAI/Tools/Toolkits/ToolkitInterface.php',
    __DIR__.'/NeuronAI/Tools/ProviderToolInterface.php',
    __DIR__.'/NeuronAI/Tools/ToolProperty.php',
    __DIR__.'/NeuronAI/Tools/Tool.php',
    __DIR__.'/NeuronAI/Chat/Messages/Message.php',
    __DIR__.'/NeuronAI/Chat/Messages/UserMessage.php',
    __DIR__.'/NeuronAI/Chat/Messages/AssistantMessage.php',
    __DIR__.'/NeuronAI/Chat/Messages/Stream/Chunks/TextChunk.php',
    __DIR__.'/NeuronAI/Chat/Messages/Stream/StreamHandler.php',
    __DIR__.'/NeuronAI/Chat/History/ChatHistoryInterface.php',
    __DIR__.'/NeuronAI/Agent/Agent.php',
];

foreach ($stubFiles as $stubFile) {
    require_once $stubFile;
}
