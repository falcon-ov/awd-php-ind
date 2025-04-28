<?php
/**
 * Валидация входных данных.
 * @param string $input
 * @return string
 */
function validateInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}