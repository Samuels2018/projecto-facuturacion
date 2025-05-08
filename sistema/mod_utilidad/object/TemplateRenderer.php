<?php
class TemplateRenderer {
    private array $slots = [];

    // Método para asignar contenido a un slot
    public function setSlot(string $slotName, string $content) {
        $this->slots[$slotName] = $content;
    }

    // Método para renderizar la plantilla con los slots inyectados
    public function render(string $templatePath) {
        if (!file_exists($templatePath)) {
          echo $templatePath;
            die("Error: La plantilla no existe.");
        }

        // Extraer los slots en variables
        extract($this->slots);
        
        // Capturar la salida y procesar la plantilla
        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}
?>