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
    // 'stats' => false,
    // 'inputPath' => '.tests/input/',
    // 'inputPattern' => '%p_i%t.txt',
    // 'expectedPath' => '.tests/expected/',
    // 'expectedPattern' => '%p_e%t.txt',
    // 'outputPath' => '.tests/output/',
    // 'outputPattern' => '%p_o%t_%l.txt',
    // 'debugLog' => '.tests/output/_debug_log.txt',
    // 'buildLog' => '.tests/output/_build_log.txt',
    // == Patterns available in 'inputPattern', 'expectedPattern', 'outputPattern':
    //     %l languageName
    //     %p puzzleName
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
    // Additional languages, unsupported on CodinGame:
        // 'cobol',
        // 'fortran',
        // 'r',
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
    'c++' => [
        'includePuzzles' => [
            'puzzle/cg/expert/' => [
                'expert_nintendo_sponsored_contest',
            ],
        ],
    ],
    'php' => [
        // 'sourcePath' => '', // uncomment if source code is not in 'php/' but in the keys, e.g. 'puzzle/...'
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
                'easy_com_1_ngr_basic_radar',
                'easy_com_1000000000d_world',
                'easy_com_10_pin_bowling_scores',
                'easy_com_111_rubiks_cube_movements',
                'easy_com_1d_spreadsheet',
                'easy_com_24_game',
                'easy_com_2nd_degree_polynomial_simple_analysis',
                'easy_com_7_segment_scanner',
                'easy_com_add_em_up',
                'easy_com_annihilation',
                'easy_com_are_the_clumps_normal',
                'easy_com_artificial_emotional_intelligence',
                'easy_com_asteroids',
                'easy_com_auto_pickup',
                'easy_com_a_bunny_and_carrots',
                'easy_com_a_childs_play',
                'easy_com_a_mountain_of_a_mole_hill',
                'easy_com_balanced_ternary_computer_encode',
                'easy_com_ball_trajectory_predictor',
                'easy_com_bank_robbers',
                'easy_com_benfords_law',
                'easy_com_bet_payout_calculator',
                'easy_com_binary_image',
                'easy_com_blowing_fuse',
                'easy_com_body_weight_is_a_girls_secret',
                'easy_com_brackets_extreme_ed',
                'easy_com_brick_in_the_wall',
                'easy_com_bulk_email_generator',
                'easy_com_buzzle',
                'easy_com_by_train_or_by_car',
                'easy_com_caesar_is_the_chief',
                'easy_com_calculator',
                'easy_com_code_breaker_puzzle',
                'easy_com_container_terminal',
                'easy_com_cosmic_love',
                'easy_com_crop_circles',
                'easy_com_currency_conversion_challenge',
                'easy_com_custom_game_of_life',
                'easy_com_darts',
                'easy_com_dead_mens_shot',
                'easy_com_decode_the_message',
                'easy_com_die_handedness',
                'easy_com_disordered_first_contact',
                'easy_com_distributing_candy',
                'easy_com_dolbears_law',
                'easy_com_duck_hunt',
                'easy_com_dungeons_and_maps',
                'easy_com_encryptiondecryption_software',
                'easy_com_encryption_enigma_machine',
                'easy_com_equivalent_resistance_circuit_building',
                'easy_com_extended_hamming_codes',
                'easy_com_faro_shuffle',
                // todo: add more test cases
                // '',
            ],
            'puzzle/community/medium/' => [
                'med_com_2_5d_maze',
                'med_com_tennis_score',
                // todo: add more test cases
                // '',
            ],
            'puzzle/community/hard/' => [
                'hard_com_3d_ascii_cube',
                'hard_com_7_segment_display',
                'hard_com_nonogram_inversor',
                'hard_com_sliding_maze_puzzle',
                // todo: add more test cases
                // '',
            ],
            'puzzle/community/expert/' => [
                'expert_com_binary_neural_network_part_2',
                'expert_com_cg_chat_interpreter_part_1',
                'expert_com_chemical_equation_balancing',
                'expert_com_codindice',
                'expert_com_completed_mahjong_hands',
                'expert_com_cross_the_lines',
                'expert_com_cubax_folding',
                'expert_com_dungeon_designer',
                'expert_com_fill_the_square',
                'expert_com_flood_the_world',
                'expert_com_haunted_manor',
                'expert_com_heart_of_the_city',
                'expert_com_high_rise_buildings',
                'expert_com_hitori_solver',
                'expert_com_hourglass',
                'expert_com_king_of_diamonds',
                'expert_com_mathematics_for_big_ears',
                'expert_com_minimax_simple_example',
                'expert_com_nurikabe',
                'expert_com_prime_fractals_in_pascals_triangle',
                'expert_com_prime_transformations',
                'expert_com_skylines',
                'expert_com_sliding_puzzle',
                'expert_com_spy_the_spies',
                'expert_com_squares_order',
                'expert_com_the_barnyard',
                'expert_com_the_crime_scene',
                'expert_com_the_lucky_number',
                'expert_com_the_two_piles_difference',
                'expert_com_the_water_jug_riddle_from_die_hard_3',
                'expert_com_ticket_to_ride_europe',
                'expert_com_tiling_squares',
                'expert_com_unflood_the_world',
                'expert_com_unfolding_paper',
                'expert_com_when_pigs_fly',
                'expert_com_xorandor',
            ],
            'puzzle/own/hello_world/' => [
                'my_easy_com_hello_world',
            ],
            'puzzle/own/lets_go_to_the_cinema/' => [
                'my_med_com_lets_go_to_the_cinema',
            ],
            'puzzle/own/source_code_analyser/' => [
                'my_med_com_source_code_analyser',
            ],
        ],
        // Note: The following puzzles cannot be tested with CGTest, as they are NOT simple I/O puzzles:
        // 'excludePuzzles' => [
        //     'codegolf_dont_panic',
        //     'codegolf_power_of_thor',
        //     'tutorial_onboarding',
        //     'easy_mars_lander_ep1',
        //     'easy_power_of_thor_ep1',
        //     'easy_the_descent',
        //     'med_dont_panic_ep1',
        //     'med_mars_lander_ep2',
        //     'med_shadows_of_the_knight_ep1',
        //     'med_skynet_revolution_ep1',
        //     'med_the_last_crusade_ep1',
        //     'med_there_is_no_spoon_ep1',
        //     'hard_dont_panic_ep2',
        //     'hard_power_of_thor_episode_2',
        //     'hard_skynet_revolution_ep2',
        //     'hard_the_bridge_episode_2',
        //     'hard_the_labyrinth',
        //     'hard_the_last_crusade_episode_2',
        //     'hard_there_is_no_spoon_episode_2',
        //     'hard_vox_codei_episode_1',
        //     'expert_mars_lander_ep3',
        //     'expert_shadows_of_the_knight_ep2',
        //     'expert_vox_codei_episode_2',
        //     'expert_the_last_crusade_episode_3',
        //     'easy_com_catching_up',
        //     'med_com_can_you_save_the_forest_episode_1',
        //     'med_com_escaping_the_cat',
        //     'med_com_forest_fire',
        //     'med_com_minesweeper_1',
        //     'hard_com_can_you_save_the_forest_episode_2',
        //     'hard_com_freecell',
        //     'hard_com_sokoban',
        //     'expert_com_breach',
        // ],
    ],
];
