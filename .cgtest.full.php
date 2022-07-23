<?php

/**
 * CGTest v1.0.0 - configuration file
 *
 * A multi-language offline batch test runner for CodinGame (or other) solo I/O puzzles.
 * (c) 2022, by Balint Toth [TBali]
 */

declare(strict_types=1);

namespace TBali\CGTest;

return [
    // == The following default config options can be overriden:
    // 'dry-run' => false,
    // 'ansi' => true,
    // 'verbose' => false,
    // 'inputPath' => '.tests/input/',
    // 'inputPattern' => '%p_i%t.txt',
    // 'expectedPath' => '.tests/expected/',
    // 'expectedPattern' => '%p_e%t.txt',
    // 'outputPath' => '.tests/output/',
    // 'outputPattern' => '%p_o%t_%l.txt',
    // 'execPattern' => '%p_%l.exe',        // used only with --clean CLI argument
    // 'debugLog' => '.tests/output/_debug_log.txt',
    // 'buildLog' => '.tests/output/_build_log.txt',
    // == Patterns available in 'inputPattern', 'expectedPattern', 'outputPattern', 'execPattern':
    //     %l languageName
    //     %p puzzleName
    // == Patterns available in 'inputPattern', 'expectedPattern', 'outputPattern':
    //     %t testIndex [01..99]
    // == Tests will be run in the following languages:
    'languages' => [
        // 'bash',
        'c',
        // 'c#',
        'c++',
        // 'clojure',
        'd',
        'dart',
        // 'f#',
        'go',
        'groovy',
        'haskell',
        'java',
        // 'javascript',
        'kotlin',
        'lua',
        // 'objective-c',
        // 'ocaml',
        'pascal',
        'perl',
        'php',
        'python',
        'ruby',
        'rust',
        'scala',
        // 'swift',
        // 'typescript',
        // 'vb.net',
    ],
    // == Tests will be run for the following puzzles in all languages (can be overriden in the per-language config):
    // 'puzzles' => [
    //     'path/' => [
    //         'puzzleName',
    //     ],
    // ],
    'puzzles' => [
        'codegolf/' => [
            'codegolf_chuck_norris',
            'codegolf_temperatures',
        ],
        'puzzle/cg/easy/' => [
            'easy_ascii_art',
            'easy_chuck_norris',
            'easy_horse_racing_duals',
            'easy_temperatures',
        ],
        'puzzle/cg/medium/' => [
            'med_stock_exchange_losses',
        ],
        'puzzle/cg/hard/' => [
        ],
        'puzzle/cg/expert/' => [
        ],
        'puzzle/community/easy/' => [
            'easy_com_1d_bush_fire',
            'easy_com_count_as_i_count',
            'easy_com_create_the_longest_sequence_of_1s',
            'easy_com_credit_card_verifier_luhns_algorithm',
            'easy_com_robot_show',
            'easy_com_smooth',
            'easy_com_sum_of_spirals_diagonals',
            'easy_com_the_mystic_rectangle',
            'easy_com_the_river_1',
            'easy_com_the_river_2',
            'easy_com_unit_fractions',
        ],
        'puzzle/community/medium/' => [
            'med_com_bit_count_to_limit',
            'med_com_factorial_vs_exponential',
            'med_com_halting_sequences',
            'med_com_minimal_number_of_swaps',
            'med_com_porcupine_fever',
            'med_com_remaining_card',
            'med_com_reversed_look_and_say',
            'med_com_rubik',
            'med_com_sum_of_divisors',
            'med_com_the_experience_for_creating_puzzles',
            'med_com_the_fastest',
        ],
        'puzzle/community/hard/' => [
            'hard_com_execution_circle',
            'hard_com_google_interview_the_two_egg_problem',
            'hard_com_highest_truncated_pyramid',
        ],
        'puzzle/community/expert/' => [
            'expert_com_recurring_decimals',
        ],
    ],
    // == The following per-language config options are available:
    // 'languageName' => [
    //     // IMPORTANT NOTE: if 'sourcePath' is given, it will OVERRIDE the 'path/' keys
    //     //   in the 'puzzles' and 'includePuzzles' lists.
    //     'sourcePath' => 'language/',
    //     'sourceExtension' => '.lang',
    //     'codinGameVersion' => '',
    //     'versionCommand' => 'lang --version',
    //     'buildCommand' => '',
    //     'runCommand' => 'lang %s',
    //     'cleanPatterns' => [
    //         'fileNameWithPathOrPattern',
    //     ],
    //     'excludePuzzles' => [
    //         'puzzleName',
    //     ],
    //     'includePuzzles' => [
    //         'path/' => [
    //             'puzzleName',
    //         ],
    //     ],
    // ],
    // == Patterns available in 'buildCommand', 'runCommand', 'cleanPatterns':
    //     %l languageName
    //     %p puzzleName
    //     %o outputPath
    // == Patterns available in 'buildCommand', 'runCommand':
    //     %s sourceFileName (with path and extension)
    // == Example (not really needed here, as these are the default settings for rust):
    'rust' => [
        'sourcePath' => 'rust/',
        'sourceExtension' => '.rs',
        'versionCommand' => 'rustc --version',
        'buildCommand' => 'rustc %s -o%o%p_%l.exe',
        'runCommand' => '%o%p_%l.exe',
        'cleanPatterns' => [
            '%o%p_%l.exe',
            '%o%p_%l.pdb',
        ],
        'excludePuzzles' => [
        ],
        'includePuzzles' => [
        ],
    ],
    // == Additional test cases for a single language:
    'php' => [
        'includePuzzles' => [
            'puzzle/cg/easy/' => [
                'easy_defibrillators',
                'easy_mime_type',
            ],
            'puzzle/cg/medium/' => [
                'med_bender_ep1',
                'med_conway_sequence',
                'med_dwarves_standing_on_shoulders_of_giants',
                'med_mayan_calculation',
                'med_network_cabling',
                'med_scrabble',
                'med_telephone_numbers',
                'med_the_gift',
                'med_war',
            ],
            'puzzle/cg/hard/' => [
                'hard_bender_ep2',
                'hard_bender_ep3',
                'hard_cgx_formatter',
                'hard_genome_sequencing',
                'hard_roller_coaster',
                'hard_super_computer',
                'hard_surface',
                'hard_tan_network',
                'hard_winamax_sponsored_contest',
            ],
            'puzzle/cg/expert/' => [
                'expert_music_scores',
                'expert_the_resistance',
            ],
            'puzzle/community/easy/' => [
                'easy_com_crop_circles',
            ],
            'puzzle/community/medium/' => [
                'med_com_tennis_score',
            ],
            'puzzle/community/hard/' => [
                'hard_com_7_segment_display',
            ],
            'puzzle/community/hard/nonogram_inversor/' => [
                'hard_com_nonogram_inversor',
            ],
            'puzzle/community/hard/sliding_maze_puzzle/' => [
                'hard_com_sliding_maze_puzzle',
            ],
            'puzzle/community/expert/' => [
                'expert_com_binary_neural_network_part_2',
            ],
            'puzzle/community/expert/flood_the_world/' => [
                'expert_com_flood_the_world',
            ],
            'puzzle/own/hello_world/' => [
                'my_easy_com_hello_world',
            ],
            'puzzle/own/lets_go_to_the_cinema/' => [
                'my_med_com_lets_go_to_the_cinema',
            ],
            'puzzle/own/source_code_analyser/' => [
                'my_med_source_code_analyser',
            ],
        ],
    ],
];
