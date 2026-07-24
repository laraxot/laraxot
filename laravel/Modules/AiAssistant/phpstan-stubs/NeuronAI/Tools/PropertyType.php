<?php

declare(strict_types=1);

namespace NeuronAI\Tools;

enum PropertyType
{
    case STRING;

    case INTEGER;

    case NUMBER;

    case BOOLEAN;

    case ARRAY;
}
