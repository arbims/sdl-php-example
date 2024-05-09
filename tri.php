<?php

declare(strict_types=1);

require 'bootstrap.php';

$x = 50;
$y = 50;

const WINDOW_WIDTH = 840;
const WINDOW_HEIGHT = 680;

$x = 1;
$y = 1;
$dx = 1;
$dy = 1;
$window = SDL_CreateWindow('Primitive drawing example', SDL_WINDOWPOS_UNDEFINED, SDL_WINDOWPOS_UNDEFINED, WINDOW_WIDTH, WINDOW_HEIGHT, SDL_WINDOW_SHOWN);
$renderer = SDL_CreateRenderer($window, 0, SDL_RENDERER_SOFTWARE);
SDL_SetHint(SDL_HINT_RENDER_SCALE_QUALITY, "nearest");
SDL_RenderSetLogicalSize($renderer, WINDOW_WIDTH, WINDOW_HEIGHT);

function SDL_TRIANGLE($vertices, $renderer) {
    // Sort vertices by Y-coordinate
    usort($vertices, function($a, $b) {
        return $a['y'] <=> $b['y'];
    });

    // Calculate the slope of the left and right sides of the triangle
    $slope_left = ($vertices[1]['x'] - $vertices[0]['x']) / ($vertices[1]['y'] - $vertices[0]['y']);
    $slope_right = ($vertices[2]['x'] - $vertices[0]['x']) / ($vertices[2]['y'] - $vertices[0]['y']);

    // Fill the triangle by drawing horizontal lines between the vertices
    for ($y = $vertices[0]['y']; $y <= $vertices[1]['y']; $y++) {
        $x1 = $vertices[0]['x'] + ($y - $vertices[0]['y']) * $slope_left;
        $x2 = $vertices[0]['x'] + ($y - $vertices[0]['y']) * $slope_right;
        SDL_RenderDrawLine($renderer, (int) $x1, $y, (int) $x2, $y);
    }
}
$quit = false;
$event = new SDL_Event;
while (!$quit) {
    SDL_SetRenderDrawColor($renderer, 0, 0, 0, 0);
    SDL_RenderClear($renderer);

    // Define triangle vertices
    $vertices = array(
        array('x' => 400, 'y' => 100), // Top vertex
        array('x' => 200, 'y' => 400), // Bottom-left vertex
        array('x' => 600, 'y' => 400), // Bottom-right vertex
    );

    // Fill the triangle with a single color
    SDL_SetRenderDrawColor($renderer, 255, 0, 255, 0); // Red color

    SDL_TRIANGLE($vertices, $renderer);

    SDL_RenderPresent($renderer);

    SDL_PollEvent($event);
    SDL_Delay(10);
    while (SDL_PollEvent($event)) {

        switch ($event->type) {
            case SDL_QUIT:
                $quit = true;
                break;
            case SDL_KEYDOWN:
                // Check for key press events
                $key = $event->key->keysym->sym;
                if (chr($key) == 'q') {
                    $quit = true;
                }
                break;
        }
    }
}

SDL_DestroyRenderer($renderer);
SDL_DestroyWindow($window);
SDL_Quit();
