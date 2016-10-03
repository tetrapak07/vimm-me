<?php

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Rating;
use App\Models\Answer;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        /* allow mass assigments */
        //Model::unguard(); 

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->call('SettingsTableSeeder');
        $this->call('QuestionsTableSeeder');
        $this->call('AnswersTableSeeder');
        $this->call('MembersTableSeeder');
        $this->call('AdminsTableSeeder');
        $this->call('MembersRatingsTableSeeder');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}

class SettingsTableSeeder extends Seeder {

    public function run() {

        DB::table('settings')->truncate();

        $settings = array(
            ['id' => 1, 'name' => 'uri_site', 'value' => URL::to('/'),
                'description' => 'Базовый адрес сайта',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 2, 'name' => 'domain', 'value' => Request::server('HTTP_HOST'),
                'description' => 'Базовый домен сайта',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 3, 'name' => 'site_name', 'value' => 'Имя сайта',
                'description' => 'Название сайта',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 4, 'name' => 'title', 'value' => 'Тайтл',
                'description' => 'Основная часть заголовка сайта',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 5, 'name' => 'description', 'value' => 'Описание сайта',
                'description' => 'Основное описание сайта',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 6, 'name' => 'keywords', 'value' => 'ключевое, слово, тест1',
                'description' => 'Основная часть ключевых слов сайта',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 7, 'name' => 'main_page_h2', 'value' => 'Оглавление главной страницы',
                'description' => 'Оглавление главной страницы',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ],
            ['id' => 8, 'name' => 'content', 'value' => '<div class="jumbotron">
  <p>Желаете, чтобы Ваш <b>оригинальный или смешной вопрос</b> стал популярным?</p>
  <p>И как можно больше людей отреагировало на него?</p>
  <p>Тогда...</p>
  <p><a class="btn btn-primary btn-lg">Создайте ВИДЕО-ВОПРОС!</a></p>
  <p>И получайте на него еще более <b>оригинальные и забавные ВИДЕО-ОТВЕТЫ</b>!</p>
  <p>Кроме этого, у Вас всегда есть возможность общаться, <b>отвечая на чужие ВИДЕО-ВОПРОСЫ.</b></p>
</div>',
                'description' => 'Главная страница - Контент',
                'created_at' => new DateTime, 'updated_at' => new DateTime
            ]
        );

        DB::table('settings')->insert($settings);
    }

}

class QuestionsTableSeeder extends Seeder {

    public function run() {

        DB::table('questions')->truncate();
        foreach (range(1, 10) as $num) {
            Question::create([
                'title' => 'question title ' . $num,
                'member_id' => 1,
                'rating_id' => Rating::create([
                    'rating_plus' => 1,
                    'rating_minus' => 0,
                    'summary' => 1
                ])->id,
                'slug' => 'question-slug-' . $num,
                'url_thumb' => 'https://i.ytimg.com/vi/jA7bMfV1COs/hqdefault.jpg',
                'url' => 'https://www.youtube.com/v/jA7bMfV1COs',
                'rem' => 'jA7bMfV1COs'
            ]);
        }
    }

}

class AnswersTableSeeder extends Seeder {

    public function run() {

        DB::table('answers')->truncate();
        foreach (range(1, 10) as $numId) {
            foreach (range(1, 10) as $num) {
                Answer::create([
                    'title' => 'answer title ' . $num . '-' . $numId,
                    'question_id' => $numId,
                    'member_id' => 1,
                    'rating_id' => Rating::create([
                        'rating_plus' => 0,
                        'rating_minus' => 1,
                        'summary' => -1
                    ])->id,
                    'slug' => 'anwer-slug-' . $num . '-' . $numId,
                    'url_thumb' => 'https://i.ytimg.com/vi/QBYLVXyvRWE/hqdefault.jpg',
                    'url' => 'https://www.youtube.com/v/QBYLVXyvRWE',
                    'rem' => 'QBYLVXyvRWE'
                ]);
            }
        }
    }

}

class MembersTableSeeder extends Seeder {

    public function run() {

        DB::table('members')->truncate();

        $settings = array(
            ['id' => 1,
                'ip' => '127.0.0.1',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            ['id' => 2,
                'ip' => '127.0.0.2',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ]
        );

        DB::table('members')->insert($settings);
    }

}

class AdminsTableSeeder extends Seeder {

    public function run() {

        DB::table('administrators')->truncate();

        $administrators = array(
            ['id' => 1,
                'username' => 'admin',
                'password' => '$2y$10$UeRaGHVb78d3DNGD/PnEkS6/zceMqGePU.8Nfl0Qh1gfdssauRNrOjibxhHa',
                'name' => 'Administrator',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ]
        );

        DB::table('administrators')->insert($administrators);
    }

}

class MembersRatingsTableSeeder extends Seeder {

    public function run() {
        DB::table('members_ratings')->truncate();

        foreach (range(1, 110) as $num) {

            $membersRatings = array(
                ['member_id' => rand(1, 2),
                    'rating_id' => $num
                ]
            );
            DB::table('members_ratings')->insert($membersRatings);
        }
    }

}
