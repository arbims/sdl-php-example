<?php

// Initialize SDL
if (SDL_Init(SDL_INIT_VIDEO) < 0) {
    echo "SDL could not initialize! SDL_Error: " . SDL_GetError() . "\n";
    exit(1);
}

// Create a window
$window = SDL_CreateWindow("SDL Render Multicolor Triangle Example", SDL_WINDOWPOS_UNDEFINED, SDL_WINDOWPOS_UNDEFINED, 800, 600, SDL_WINDOW_SHOWN);
if (!$window) {
    echo "Window could not be created! SDL_Error: " . SDL_GetError() . "\n";
    exit(1);
}

// Create a renderer
$renderer = SDL_CreateRenderer($window, -1, SDL_RENDERER_ACCELERATED);
if (!$renderer) {
    echo "Renderer could not be created! SDL_Error: " . SDL_GetError() . "\n";
    exit(1);
}

// Clear the screen
SDL_SetRenderDrawColor($renderer, 0, 0, 0, 255);
SDL_RenderClear($renderer);

// Define triangle vertices and colors
$vertices = array(
    array('x' => 400, 'y' => 100, 'r' => 255, 'g' => 0, 'b' => 0), // Top vertex (red)
    array('x' => 200, 'y' => 400, 'r' => 0, 'g' => 255, 'b' => 0), // Bottom-left vertex (green)
    array('x' => 600, 'y' => 400, 'r' => 0, 'g' => 0, 'b' => 255), // Bottom-right vertex (blue)
);

// Sort vertices by Y-coordinate
usort($vertices, function($a, $b) {
    return $a['y'] <=> $b['y'];
});

// Check if the triangle is degenerate
if ($vertices[0]['y'] == $vertices[2]['y']) {
    echo "Degenerate triangle: All vertices are on the same horizontal line.\n";
    exit(1);
}

// Fill the triangle by dividing it into smaller triangles
for ($y = $vertices[0]['y']; $y <= $vertices[2]['y']; $y++) {
    // Calculate the left and right edges of the triangle at this Y-coordinate
    $x1 = $vertices[0]['x'] + ($y - $vertices[0]['y']) * ($vertices[2]['x'] - $vertices[0]['x']) / ($vertices[2]['y'] - $vertices[0]['y']);
    $x2 = ($y < $vertices[1]['y']) ? $vertices[0]['x'] + ($y - $vertices[0]['y']) * ($vertices[1]['x'] - $vertices[0]['x']) / ($vertices[1]['y'] - $vertices[0]['y']) : $vertices[0]['x'] + ($y - $vertices[0]['y']) * ($vertices[2]['x'] - $vertices[0]['x']) / ($vertices[2]['y'] - $vertices[0]['y']);

    // Interpolate colors along the edges
    $color1 = SDL_MapRGB(SDL_AllocFormat(SDL_PIXELFORMAT_RGBA8888), $vertices[0]['r'] + ($vertices[2]['r'] - $vertices[0]['r']) * ($x1 - $vertices[0]['x']) / ($vertices[2]['x'] - $vertices[0]['x']), $vertices[0]['g'] + ($vertices[2]['g'] - $vertices[0]['g']) * ($x1 - $vertices[0]['x']) / ($vertices[2]['x'] - $vertices[0]['x']), $vertices[0]['b'] + ($vertices[2]['b'] - $vertices[0]['b']) * ($x1 - $vertices[0]['x']) / ($vertices[2]['x'] - $vertices[0]['x']));
    $color2 = ($y < $vertices[1]['y']) ? SDL_MapRGB(SDL_AllocFormat(SDL_PIXELFORMAT_RGBA8888), $vertices[0]['r'] + ($vertices[1]['r'] - $vertices[0]['r']) * ($x2 - $vertices[0]['x']) / ($vertices[1]['x'] - $vertices[0]['x']), $vertices[0]['g'] + ($vertices[1]['g'] - $vertices[0]['g']) * ($x2 - $vertices[0]['x']) / ($vertices[1]['x'] - $vertices[0]['x']), $vertices[0]['b'] + ($vertices[1]['b'] - $vertices[0]['b']) * ($x2 - $vertices[0]['x']) / ($vertices[1]['x'] - $vertices[0]['x'])) : SDL_MapRGB(SDL_AllocFormat(SDL_PIXELFORMAT_RGBA8888), $vertices[0]['r'] + ($vertices[2]['r'] - $vertices[0]['r']) * ($x2 - $vertices[0]['x']) / ($vertices[2]['x'] - $vertices[0]['x']), $vertices[0]['g'] + ($vertices[2]['g'] - $vertices[0]['g']) * ($x2 - $vertices[0]['x']) / ($vertices[2]['x'] - $vertices[0]['x']), $vertices[0]['b'] + ($vertices[2]['b'] - $vertices[0]['b']) * ($x2 - $vertices[0]['x']) / ($vertices[2]['x'] - $vertices[0]['x']));

    // Draw a line between the left and right edges
    SDL_SetRenderDrawColor($renderer, ($color1 >> 24) & 0xFF, ($color1 >> 16) & 0xFF, ($color1 >> 8) & 0xFF, ($color1) & 0xFF);
    SDL_RenderDrawLine($renderer, $x1, $y, $x2, $y);
}

// Present the renderer
SDL_RenderPresent($renderer);

// Delay to show the window
SDL_Delay(5000);

// Clean up
SDL_DestroyRenderer($renderer);
SDL_DestroyWindow($window);
SDL_Quit();

?>
