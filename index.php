<?php

declare(strict_types=1);

require 'bootstrap.php';

$x = 50;
$y = 50;

const RECT_SIZE = 40;
const WINDOW_WIDTH = 840;
const WINDOW_HEIGHT = 680;
const FRAME = 4;
const BAR_LEN = 100;
const TARGET_WIDTH = BAR_LEN;
const BAR_THICCNESS = 20;
const TARGET_PADD = BAR_THICCNESS;

$x = 1;
$y = 1;
$dx = 1;
$dy = 1;
$bar_x = 100;
$bar_y = 600;
$window = SDL_CreateWindow('Primitive drawing example', SDL_WINDOWPOS_UNDEFINED, SDL_WINDOWPOS_UNDEFINED, WINDOW_WIDTH, WINDOW_HEIGHT, SDL_WINDOW_SHOWN);
$renderer = SDL_CreateRenderer($window, 0, SDL_RENDERER_SOFTWARE);
SDL_SetHint(SDL_HINT_RENDER_SCALE_QUALITY, "nearest");
SDL_RenderSetLogicalSize($renderer, WINDOW_WIDTH, WINDOW_HEIGHT);

function draw_target($renderer)
{
	for ($j = 1; $j <= 5; $j++) {
		for ($i = 1; $i <= 5; $i++) {
			$rect = new SDL_Rect((TARGET_WIDTH + TARGET_PADD) * $i, 50 * $j, BAR_LEN, BAR_THICCNESS);
			SDL_SetRenderDrawColor($renderer, 0, 255, 0, 255);
			SDL_RenderFillRect($renderer, $rect);
		}
	}
}


$quit = false;
$event = new SDL_Event;
while (!$quit) {
	SDL_SetRenderDrawColor($renderer, 0, 0, 0, 0);
	SDL_RenderClear($renderer);


	if ($x < 0 || $x + RECT_SIZE > WINDOW_WIDTH) {
		$dx = $dx * -1;
	}
	$x = $x + FRAME * $dx;
	if ($y < 0 || $y + RECT_SIZE > WINDOW_HEIGHT) {
		$dy = $dy * -1;
	}
	$y = $y + FRAME * $dy;

	$rect = new SDL_Rect($x, $y, RECT_SIZE, RECT_SIZE);
	SDL_SetRenderDrawColor($renderer, 255, 0, 0, 255);
	SDL_RenderFillRect($renderer, $rect);

	draw_target($renderer);

	SDL_RenderPresent($renderer);

	$bar = new SDL_Rect($bar_x, $bar_y, TARGET_WIDTH, TARGET_PADD);
	SDL_SetRenderDrawColor($renderer, 255, 0, 0, 255);
	SDL_RenderFillRect($renderer, $bar);
	SDL_RenderPresent($renderer);

	// Define triangle vertices
	// $vertices = array(
	// 	array('x' => 400, 'y' => 100, 'r' => 255), // Top vertex
	// 	array('x' => 200, 'y' => 400, 'g' => 0), // Bottom-left vertex
	// 	array('x' => 600, 'y' => 400, 'b' => 0), // Bottom-right vertex
	// );

	// // Draw lines between vertices to form a triangle
	// foreach ($vertices as $index => $vertex) {
	// 	$nextIndex = ($index + 1) % count($vertices);
	// 	$nextVertex = $vertices[$nextIndex];
	// 	SDL_RenderDrawLine($renderer, $vertex['x'], $vertex['y'], $nextVertex['x'], $nextVertex['y']);
	// }

	// SDL_RenderPresent($renderer);

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
				echo chr($key). "\n";
                if (chr($key) == 'q') {
                    $quit = true;
                } elseif (chr($key) == "O" && $bar_x + TARGET_WIDTH < WINDOW_WIDTH) {
					$bar_x += 40;
					$bar->x = $bar_x;
					SDL_RenderPresent($renderer);
				} elseif(chr($key) == 'P' && $bar_x > 0) {
					$bar_x -= 40;
					print_r($bar);
					$bar->x = $bar_x;
					// SDL_RenderFillRect($renderer, $bar);
					// SDL_RenderPresent($renderer);
					SDL_RenderPresent($renderer);
				}
                break;
        }
	}
}

SDL_DestroyRenderer($renderer);
SDL_DestroyWindow($window);
SDL_Quit();
