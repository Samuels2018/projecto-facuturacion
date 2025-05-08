<?php

namespace Logger;

enum ErrorTipo: string
{
    case info = 'informativo: ';
    case warning = 'advertencia: ';
    case debug = 'depuración: ';
    case error = 'error: ';
    case fatal = 'fatal: ';
}