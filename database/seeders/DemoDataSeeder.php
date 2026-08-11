<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\AppNotification;
use App\Models\Attempt;
use App\Models\Answer;
use App\Models\BankOption;
use App\Models\BankQuestion;
use App\Models\Bookmark;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizUnlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    protected array $categories = ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'English', 'ICT', 'General Knowledge', 'History'];

    /**
     * Real MCQ content per category: question, 4 options, index of correct option.
     */
    protected array $mcqBank = [];

    /**
     * Real True/False statements per category: statement + boolean answer.
     */
    protected array $tfBank = [];

    public function __construct()
    {
        $this->mcqBank = [
            'Mathematics' => [
                ['q' => 'What is the value of π (pi) rounded to two decimal places?', 'options' => ['3.12', '3.14', '3.16', '3.18'], 'correct' => 1],
                ['q' => 'What is the square root of 144?', 'options' => ['10', '11', '12', '14'], 'correct' => 2],
                ['q' => 'Solve for x: 2x + 5 = 15', 'options' => ['5', '10', '7.5', '4'], 'correct' => 0],
                ['q' => 'What is the sum of interior angles of a triangle?', 'options' => ['90°', '180°', '270°', '360°'], 'correct' => 1],
                ['q' => 'What is 15% of 200?', 'options' => ['20', '25', '30', '35'], 'correct' => 2],
                ['q' => 'Which of these numbers is a prime number?', 'options' => ['21', '27', '29', '33'], 'correct' => 2],
                ['q' => 'What is the formula for the area of a circle?', 'options' => ['2πr', 'πr²', 'πd', '4πr²'], 'correct' => 1],
                ['q' => 'What is the value of 7! (7 factorial)?', 'options' => ['49', '720', '5040', '42'], 'correct' => 2],
                ['q' => 'A triangle has sides 3, 4, and 5. What type of triangle is it?', 'options' => ['Equilateral', 'Isosceles', 'Right-angled', 'Obtuse'], 'correct' => 2],
                ['q' => 'What is the derivative of x²?', 'options' => ['x', '2x', 'x²', '2'], 'correct' => 1],
            ],
            'Physics' => [
                ['q' => 'What is the SI unit of force?', 'options' => ['Joule', 'Newton', 'Watt', 'Pascal'], 'correct' => 1],
                ['q' => 'What is the approximate acceleration due to gravity on Earth?', 'options' => ['8.9 m/s²', '9.8 m/s²', '10.8 m/s²', '11.2 m/s²'], 'correct' => 1],
                ['q' => "Which law states that every action has an equal and opposite reaction?", 'options' => ["Newton's First Law", "Newton's Second Law", "Newton's Third Law", 'Law of Gravitation'], 'correct' => 2],
                ['q' => 'What type of energy is stored in a stretched rubber band?', 'options' => ['Kinetic', 'Potential', 'Thermal', 'Chemical'], 'correct' => 1],
                ['q' => 'What is the approximate speed of light in a vacuum?', 'options' => ['3×10⁵ km/s', '3×10⁸ m/s', '3×10⁶ m/s', '3×10³ m/s'], 'correct' => 1],
                ['q' => 'Which particle has a negative electric charge?', 'options' => ['Proton', 'Neutron', 'Electron', 'Photon'], 'correct' => 2],
                ['q' => 'What instrument is used to measure electric current?', 'options' => ['Voltmeter', 'Ammeter', 'Barometer', 'Thermometer'], 'correct' => 1],
                ['q' => 'Sound cannot travel through:', 'options' => ['Solids', 'Liquids', 'Gases', 'Vacuum'], 'correct' => 3],
                ['q' => 'What is the SI unit of electrical resistance?', 'options' => ['Ohm', 'Ampere', 'Volt', 'Watt'], 'correct' => 0],
                ['q' => 'Which of these is a vector quantity?', 'options' => ['Mass', 'Temperature', 'Velocity', 'Time'], 'correct' => 2],
            ],
            'Chemistry' => [
                ['q' => 'What is the chemical formula for water?', 'options' => ['H2O', 'O2', 'CO2', 'HO'], 'correct' => 0],
                ['q' => 'Which element has the atomic number 1?', 'options' => ['Helium', 'Hydrogen', 'Oxygen', 'Carbon'], 'correct' => 1],
                ['q' => 'What is the pH value of a neutral solution?', 'options' => ['0', '7', '14', '10'], 'correct' => 1],
                ['q' => "Which gas is most abundant in Earth's atmosphere?", 'options' => ['Oxygen', 'Carbon dioxide', 'Nitrogen', 'Hydrogen'], 'correct' => 2],
                ['q' => 'What is the chemical symbol for gold?', 'options' => ['Go', 'Gd', 'Au', 'Ag'], 'correct' => 2],
                ['q' => 'Which of these is a noble gas?', 'options' => ['Chlorine', 'Neon', 'Nitrogen', 'Fluorine'], 'correct' => 1],
                ['q' => 'What type of bond involves the sharing of electrons?', 'options' => ['Ionic bond', 'Covalent bond', 'Metallic bond', 'Hydrogen bond'], 'correct' => 1],
                ['q' => 'What is the process of a liquid turning into a gas called?', 'options' => ['Condensation', 'Sublimation', 'Evaporation', 'Deposition'], 'correct' => 2],
                ['q' => 'Which acid is commonly found in the human stomach?', 'options' => ['Sulfuric acid', 'Hydrochloric acid', 'Nitric acid', 'Acetic acid'], 'correct' => 1],
                ['q' => 'What is the atomic number of Carbon?', 'options' => ['4', '6', '8', '12'], 'correct' => 1],
            ],
            'Biology' => [
                ['q' => 'What is the powerhouse of the cell?', 'options' => ['Nucleus', 'Ribosome', 'Mitochondria', 'Golgi body'], 'correct' => 2],
                ['q' => 'Which organ pumps blood throughout the human body?', 'options' => ['Liver', 'Lungs', 'Heart', 'Kidney'], 'correct' => 2],
                ['q' => 'What is the basic unit of heredity?', 'options' => ['Cell', 'Gene', 'Chromosome', 'Protein'], 'correct' => 1],
                ['q' => 'Which blood cells help fight infection?', 'options' => ['Red blood cells', 'White blood cells', 'Platelets', 'Plasma'], 'correct' => 1],
                ['q' => 'What process do plants use to make their own food?', 'options' => ['Respiration', 'Photosynthesis', 'Transpiration', 'Digestion'], 'correct' => 1],
                ['q' => 'How many chambers does the human heart have?', 'options' => ['2', '3', '4', '5'], 'correct' => 2],
                ['q' => 'What is the largest organ in the human body?', 'options' => ['Liver', 'Brain', 'Skin', 'Lungs'], 'correct' => 2],
                ['q' => 'Which gas do plants absorb from the atmosphere for photosynthesis?', 'options' => ['Oxygen', 'Nitrogen', 'Carbon dioxide', 'Hydrogen'], 'correct' => 2],
                ['q' => 'What is the study of living organisms called?', 'options' => ['Physics', 'Biology', 'Geology', 'Chemistry'], 'correct' => 1],
                ['q' => 'Which part of the plant primarily conducts photosynthesis?', 'options' => ['Root', 'Stem', 'Leaf', 'Flower'], 'correct' => 2],
            ],
            'English' => [
                ['q' => 'Which word is a synonym for "happy"?', 'options' => ['Sad', 'Joyful', 'Angry', 'Tired'], 'correct' => 1],
                ['q' => 'What is the plural form of "child"?', 'options' => ['Childs', 'Childes', 'Children', 'Childrens'], 'correct' => 2],
                ['q' => 'Identify the noun in the sentence: "The dog ran quickly."', 'options' => ['Ran', 'Quickly', 'Dog', 'The'], 'correct' => 2],
                ['q' => 'Which of these is a proper noun?', 'options' => ['City', 'London', 'River', 'Mountain'], 'correct' => 1],
                ['q' => 'What is the past tense of "go"?', 'options' => ['Goed', 'Gone', 'Went', 'Going'], 'correct' => 2],
                ['q' => 'Which sentence is grammatically correct?', 'options' => ["She don't like tea", "She doesn't likes tea", "She doesn't like tea", 'She not like tea'], 'correct' => 2],
                ['q' => 'What is an antonym for "difficult"?', 'options' => ['Hard', 'Easy', 'Complex', 'Tough'], 'correct' => 1],
                ['q' => 'Which part of speech describes an action?', 'options' => ['Noun', 'Adjective', 'Verb', 'Adverb'], 'correct' => 2],
                ['q' => 'Choose the correctly spelled word.', 'options' => ['Recieve', 'Receive', 'Receeve', 'Receve'], 'correct' => 1],
                ['q' => 'What type of sentence is: "What time is it?"', 'options' => ['Declarative', 'Imperative', 'Interrogative', 'Exclamatory'], 'correct' => 2],
            ],
            'ICT' => [
                ['q' => 'What does CPU stand for?', 'options' => ['Central Processing Unit', 'Computer Personal Unit', 'Central Program Utility', 'Control Processing Unit'], 'correct' => 0],
                ['q' => 'Which of these is an input device?', 'options' => ['Monitor', 'Printer', 'Keyboard', 'Speaker'], 'correct' => 2],
                ['q' => 'What does "www" stand for?', 'options' => ['World Wide Web', 'World Web Wide', 'Wide World Web', 'Web World Wide'], 'correct' => 0],
                ['q' => 'Which of these is an operating system?', 'options' => ['Microsoft Word', 'Windows', 'Google Chrome', 'Photoshop'], 'correct' => 1],
                ['q' => 'What is the full form of RAM?', 'options' => ['Random Access Memory', 'Read Access Memory', 'Random Active Memory', 'Run Access Memory'], 'correct' => 0],
                ['q' => 'Which of these file extensions is used for images?', 'options' => ['.docx', '.mp3', '.jpg', '.exe'], 'correct' => 2],
                ['q' => 'What does HTML stand for?', 'options' => ['HyperText Markup Language', 'HighText Machine Language', 'HyperTransfer Markup Language', 'HyperText Modern Language'], 'correct' => 0],
                ['q' => 'Which of these is a web browser?', 'options' => ['Excel', 'Chrome', 'Windows', 'Python'], 'correct' => 1],
                ['q' => 'What is the smallest unit of data in a computer?', 'options' => ['Byte', 'Bit', 'Kilobyte', 'Nibble'], 'correct' => 1],
                ['q' => 'Which device connects a computer to the internet?', 'options' => ['Modem', 'Monitor', 'Mouse', 'Scanner'], 'correct' => 0],
            ],
            'General Knowledge' => [
                ['q' => 'What is the capital of Bangladesh?', 'options' => ['Chittagong', 'Dhaka', 'Sylhet', 'Khulna'], 'correct' => 1],
                ['q' => 'Which is the largest ocean in the world?', 'options' => ['Atlantic', 'Indian', 'Pacific', 'Arctic'], 'correct' => 2],
                ['q' => 'How many continents are there on Earth?', 'options' => ['5', '6', '7', '8'], 'correct' => 2],
                ['q' => 'Which planet is known as the Red Planet?', 'options' => ['Venus', 'Mars', 'Jupiter', 'Saturn'], 'correct' => 1],
                ['q' => 'Who wrote the national anthem of Bangladesh?', 'options' => ['Kazi Nazrul Islam', 'Rabindranath Tagore', 'Jasimuddin', 'Sukumar Ray'], 'correct' => 1],
                ['q' => 'Which is the longest river in the world?', 'options' => ['Amazon', 'Nile', 'Yangtze', 'Padma'], 'correct' => 1],
                ['q' => 'What is the currency of Bangladesh?', 'options' => ['Rupee', 'Taka', 'Dollar', 'Dinar'], 'correct' => 1],
                ['q' => 'In which year did Bangladesh gain independence?', 'options' => ['1969', '1970', '1971', '1972'], 'correct' => 2],
                ['q' => 'Which is the smallest country in the world by area?', 'options' => ['Monaco', 'Vatican City', 'San Marino', 'Malta'], 'correct' => 1],
                ['q' => 'How many players are there in a football team on the field?', 'options' => ['9', '10', '11', '12'], 'correct' => 2],
            ],
            'History' => [
                ['q' => 'Who was the first President of independent Bangladesh?', 'options' => ['Ziaur Rahman', 'Sheikh Mujibur Rahman', 'Hussain Muhammad Ershad', 'Khondaker Mostaq Ahmad'], 'correct' => 1],
                ['q' => 'In which year did World War II end?', 'options' => ['1943', '1945', '1947', '1950'], 'correct' => 1],
                ['q' => 'The Mughal Empire primarily ruled over which region?', 'options' => ['Middle East', 'Indian Subcontinent', 'Southeast Asia', 'East Africa'], 'correct' => 1],
                ['q' => 'Who is known as the "Father of the Nation" in Bangladesh?', 'options' => ['Sheikh Mujibur Rahman', 'Ziaur Rahman', 'Suhrawardy', 'Fazlul Huq'], 'correct' => 0],
                ['q' => 'The Liberation War of Bangladesh took place in which year?', 'options' => ['1969', '1970', '1971', '1972'], 'correct' => 2],
                ['q' => 'Which ancient civilization built the pyramids of Giza?', 'options' => ['Roman', 'Greek', 'Egyptian', 'Mesopotamian'], 'correct' => 2],
                ['q' => 'The Renaissance began in which country?', 'options' => ['France', 'Italy', 'Spain', 'England'], 'correct' => 1],
                ['q' => 'Who was the first Prime Minister of independent India?', 'options' => ['Mahatma Gandhi', 'Jawaharlal Nehru', 'Sardar Patel', 'Subhas Chandra Bose'], 'correct' => 1],
                ['q' => 'The fall of the Berlin Wall occurred in which year?', 'options' => ['1985', '1989', '1991', '1993'], 'correct' => 1],
                ['q' => 'Which empire was ruled by Julius Caesar?', 'options' => ['Greek Empire', 'Roman Empire', 'Persian Empire', 'Ottoman Empire'], 'correct' => 1],
            ],
        ];

        $this->tfBank = [
            'Mathematics' => [
                ['q' => 'The sum of two even numbers is always even.', 'a' => true],
                ['q' => 'Zero is a positive number.', 'a' => false],
                ['q' => 'A right angle measures 90 degrees.', 'a' => true],
                ['q' => 'All squares are rectangles.', 'a' => true],
                ['q' => 'The number 1 is a prime number.', 'a' => false],
                ['q' => 'Division by zero is defined in mathematics.', 'a' => false],
            ],
            'Physics' => [
                ['q' => 'Heat always flows from a colder object to a hotter object on its own.', 'a' => false],
                ['q' => 'Mass and weight are the same physical quantity.', 'a' => false],
                ['q' => 'Light travels faster than sound.', 'a' => true],
                ['q' => 'A vacuum can transmit sound waves.', 'a' => false],
                ['q' => "The Earth's magnetic field has two poles.", 'a' => true],
                ['q' => 'Energy can be created and destroyed.', 'a' => false],
            ],
            'Chemistry' => [
                ['q' => 'All acids turn blue litmus paper red.', 'a' => true],
                ['q' => 'Oxygen is required for combustion.', 'a' => true],
                ['q' => 'Metals are generally poor conductors of electricity.', 'a' => false],
                ['q' => 'The atomic number of an element equals its number of protons.', 'a' => true],
                ['q' => 'Table salt (NaCl) is an example of a covalent compound.', 'a' => false],
                ['q' => 'Rust forms when iron reacts with oxygen and moisture.', 'a' => true],
            ],
            'Biology' => [
                ['q' => 'Humans have 206 bones in their body.', 'a' => true],
                ['q' => 'Plants release carbon dioxide during photosynthesis.', 'a' => false],
                ['q' => 'The human brain is part of the nervous system.', 'a' => true],
                ['q' => 'Bacteria are always harmful to humans.', 'a' => false],
                ['q' => 'DNA is found in the nucleus of a cell.', 'a' => true],
                ['q' => 'Fish breathe using lungs.', 'a' => false],
            ],
            'English' => [
                ['q' => 'A noun is a word that names a person, place, or thing.', 'a' => true],
                ['q' => 'Adjectives describe verbs.', 'a' => false],
                ['q' => "'Their', 'there', and 'they're' all mean the same thing.", 'a' => false],
                ['q' => 'A complete sentence must have a subject and a verb.', 'a' => true],
                ['q' => "'Its' and 'it's' are interchangeable.", 'a' => false],
                ['q' => 'An adverb can modify a verb.', 'a' => true],
            ],
            'ICT' => [
                ['q' => 'A byte consists of 8 bits.', 'a' => true],
                ['q' => 'Software is a physical, touchable component of a computer.', 'a' => false],
                ['q' => 'The internet and the World Wide Web are exactly the same thing.', 'a' => false],
                ['q' => 'A firewall helps protect a network from unauthorized access.', 'a' => true],
                ['q' => 'USB stands for Universal Serial Bus.', 'a' => true],
                ['q' => 'A computer virus can only affect hardware, never software.', 'a' => false],
            ],
            'General Knowledge' => [
                ['q' => 'The Great Wall of China is visible from the Moon with the naked eye.', 'a' => false],
                ['q' => 'Bangladesh is located in South Asia.', 'a' => true],
                ['q' => 'Mount Everest is the tallest mountain in the world above sea level.', 'a' => true],
                ['q' => 'The United Nations was founded in 1945.', 'a' => true],
                ['q' => 'There are seven continents on Earth.', 'a' => true],
                ['q' => 'The Pacific Ocean is smaller than the Atlantic Ocean.', 'a' => false],
            ],
            'History' => [
                ['q' => 'The Liberation War of Bangladesh took place in 1971.', 'a' => true],
                ['q' => 'The Mughal Empire was based in Europe.', 'a' => false],
                ['q' => 'World War I ended in 1918.', 'a' => true],
                ['q' => 'The Pyramids of Giza were built in Rome.', 'a' => false],
                ['q' => 'Sheikh Mujibur Rahman is considered the Father of the Nation of Bangladesh.', 'a' => true],
                ['q' => 'The Cold War involved large-scale direct battles fought on US and USSR home soil.', 'a' => false],
            ],
        ];
    }

    public function run(): void
    {
        $this->command?->info('Seeding Quizora demo data...');

        // ---------------------------------------------------------------
        // 1. TEACHERS (10)
        // ---------------------------------------------------------------
        $teachers = collect();
        $teacherNames = [
            'Rafiq Ahmed',
            'Nusrat Jahan',
            'Kamal Hossain',
            'Farhana Akter',
            'Sabbir Rahman',
            'Tanjina Islam',
            'Mahfuz Karim',
            'Shirin Sultana',
            'Imran Chowdhury',
            'Ayesha Siddika',
        ];
        foreach ($teacherNames as $i => $name) {
            $teachers->push(User::updateOrCreate(
                ['email' => 'teacher' . ($i + 1) . '@quizora.com'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'status' => 'active',
                    'phone' => '01' . rand(700000000, 999999999),
                    'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                    'institution' => ['Sylhet Cadet College', 'Notre Dame College', 'Rajshahi University School', 'BUET', 'Dhaka Residential Model College'][array_rand([0, 1, 2, 3, 4])],
                    'designation' => ['Assistant Professor', 'Senior Lecturer', 'Subject Teacher', 'Head of Department'][array_rand([0, 1, 2, 3])],
                    'preferred_language' => 'english',
                    'avatar_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'email_verified_at' => now(),
                ]
            ));
        }

        // ---------------------------------------------------------------
        // 2. STUDENTS (10)
        // ---------------------------------------------------------------
        $students = collect();
        $studentNames = [
            'Arif Hasan',
            'Mim Akter',
            'Tanvir Ahmed',
            'Nabila Rahman',
            'Shanto Das',
            'Ruma Begum',
            'Fahim Muntasir',
            'Priya Sarkar',
            'Rakibul Islam',
            'Jannatul Ferdous',
        ];
        foreach ($studentNames as $i => $name) {
            $students->push(User::updateOrCreate(
                ['email' => 'student' . ($i + 1) . '@quizora.com'],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'status' => $i === 9 ? 'suspended' : 'active',
                    'phone' => '01' . rand(300000000, 599999999),
                    'gender' => ['male', 'female'][array_rand(['male', 'female'])],
                    'institution' => ['Sylhet Cadet College', 'Notre Dame College', 'Viqarunnisa Noon School', 'Dhaka College'][array_rand([0, 1, 2, 3])],
                    'class_level' => ['Class 9', 'Class 10', 'HSC 1st Year', 'HSC 2nd Year', 'Undergraduate 1st Year'][array_rand([0, 1, 2, 3, 4])],
                    'education_level' => ['ssc', 'hsc', 'bachelor'][array_rand(['ssc', 'hsc', 'bachelor'])],
                    'study_goal' => ['exam_prep', 'university_admission', 'bcs', 'self_learning'][array_rand([0, 1, 2, 3])],
                    'preferred_language' => ['english', 'bangla'][array_rand(['english', 'bangla'])],
                    'target_score' => rand(70, 100),
                    'avatar_color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'email_verified_at' => now(),
                ]
            ));
        }

        $this->command?->info('Created ' . $teachers->count() . ' teachers and ' . $students->count() . ' students.');

        // ---------------------------------------------------------------
        // 3. QUESTION BANK per teacher (5-8 entries each, real MCQ content)
        // ---------------------------------------------------------------
        foreach ($teachers as $teacher) {
            $bankCount = rand(5, 8);
            for ($i = 0; $i < $bankCount; $i++) {
                $category = $this->categories[array_rand($this->categories)];
                $item = $this->mcqBank[$category][$i % count($this->mcqBank[$category])];

                $bq = BankQuestion::create([
                    'teacher_id' => $teacher->id,
                    'question_text' => $item['q'],
                    'marks' => [1, 2][array_rand([0, 1])],
                    'category' => $category,
                    'tags' => strtolower($category) . ',practice',
                ]);

                foreach ($item['options'] as $idx => $optionText) {
                    BankOption::create([
                        'bank_question_id' => $bq->id,
                        'option_text' => $optionText,
                        'is_correct' => $idx === $item['correct'],
                        'order' => $idx + 1,
                    ]);
                }
            }
        }
        $this->command?->info('Seeded question bank entries.');

        // ---------------------------------------------------------------
        // 4. QUIZZES per teacher (5-10 each), quiz-style only: mcq / true_false / mixed(mcq+tf)
        // ---------------------------------------------------------------
        $allQuizzes = collect();

        foreach ($teachers as $teacher) {
            $quizCount = rand(5, 10);

            for ($q = 0; $q < $quizCount; $q++) {
                $category = $this->categories[array_rand($this->categories)];
                $visibility = (($q + $teacher->id) % 3 === 0) ? 'private' : 'public';
                $status = match (true) {
                    $q === 0 => 'draft',
                    $q === 1 => 'closed',
                    default => ['active', 'active', 'closed'][array_rand([0, 1, 2])],
                };
                $type = ['mcq', 'mcq', 'true_false', 'mixed'][array_rand([0, 1, 2, 3])];

                $startsAt = now()->subDays(rand(1, 30));
                $endsAt = $status === 'closed' ? now()->subDays(rand(0, 5)) : now()->addDays(rand(3, 30));

                $quiz = Quiz::create([
                    'teacher_id' => $teacher->id,
                    'title' => $category . ' ' . ['Basics', 'Practice Test', 'Chapter Review', 'Mock Exam', 'Weekly Quiz'][array_rand([0, 1, 2, 3, 4])] . ' ' . ($q + 1),
                    'description' => 'A ' . strtolower($category) . ' assessment covering key topics for ' . strtolower($category) . ' learners.',
                    'type' => $type,
                    'status' => $status,
                    'visibility' => $visibility,
                    'access_code' => $visibility === 'private' ? strtoupper(Str::random(6)) : null,
                    'category' => $category,
                    'difficulty' => ['easy', 'medium', 'hard'][array_rand([0, 1, 2])],
                    'tags' => strtolower($category) . ',' . $type,
                    'passing_score' => [40, 50, 60][array_rand([0, 1, 2])],
                    'time_limit' => [10, 15, 20, 30][array_rand([0, 1, 2, 3])],
                    'max_attempts' => [1, 1, 2, 3][array_rand([0, 1, 2, 3])],
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'show_results' => true,
                    'shuffle_questions' => (bool) rand(0, 1),
                ]);

                $questionCount = rand(5, 12);
                $order = 1;
                $mcqCursor = 0;
                $tfCursor = 0;
                $mcqPool = $this->mcqBank[$category];
                $tfPool = $this->tfBank[$category];

                for ($i = 0; $i < $questionCount; $i++) {
                    $qType = $type === 'mixed'
                        ? (($i % 2 === 0) ? 'mcq' : 'true_false')
                        : $type; // 'mcq' or 'true_false' quizzes stay consistent

                    if ($qType === 'true_false') {
                        $item = $tfPool[$tfCursor % count($tfPool)];
                        $tfCursor++;

                        $question = Question::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $item['q'],
                            'type' => 'true_false',
                            'marks' => [1, 2][array_rand([0, 1])],
                            'order' => $order++,
                        ]);

                        Option::create(['question_id' => $question->id, 'option_text' => 'True', 'is_correct' => $item['a'] === true, 'order' => 1]);
                        Option::create(['question_id' => $question->id, 'option_text' => 'False', 'is_correct' => $item['a'] === false, 'order' => 2]);
                    } else {
                        $item = $mcqPool[$mcqCursor % count($mcqPool)];
                        $mcqCursor++;

                        $question = Question::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $item['q'],
                            'type' => 'mcq',
                            'marks' => [1, 2][array_rand([0, 1])],
                            'order' => $order++,
                        ]);

                        foreach ($item['options'] as $idx => $optionText) {
                            Option::create([
                                'question_id' => $question->id,
                                'option_text' => $optionText,
                                'is_correct' => $idx === $item['correct'],
                                'order' => $idx + 1,
                            ]);
                        }
                    }
                }

                $allQuizzes->push($quiz);
            }
        }
        $this->command?->info('Seeded ' . $allQuizzes->count() . ' quizzes with real questions and options.');

        // ---------------------------------------------------------------
        // 5. ATTEMPTS + ANSWERS (students attempting active/closed quizzes)
        // ---------------------------------------------------------------
        $attemptableQuizzes = $allQuizzes->where('status', '!=', 'draft');
        $activeStudents = $students->take(9); // exclude the suspended one

        foreach ($attemptableQuizzes as $quiz) {
            $eligibleStudents = $activeStudents;
            if ($quiz->visibility === 'private') {
                $eligibleStudents = $activeStudents->random(rand(3, 6));
                if (!$eligibleStudents instanceof \Illuminate\Support\Collection) {
                    $eligibleStudents = collect([$eligibleStudents]);
                }
                foreach ($eligibleStudents as $student) {
                    QuizUnlock::firstOrCreate([
                        'student_id' => $student->id,
                        'quiz_id' => $quiz->id,
                    ]);
                }
            }

            $attemptCount = min($eligibleStudents->count(), rand(2, 7));
            $attemptingStudents = $eligibleStudents->random($attemptCount);
            if (!$attemptingStudents instanceof \Illuminate\Support\Collection) {
                $attemptingStudents = collect([$attemptingStudents]);
            }

            $quizQuestions = $quiz->questions()->with('options')->get();
            $totalMarks = $quizQuestions->sum('marks');

            foreach ($attemptingStudents as $student) {
                $startedAt = now()->subDays(rand(0, 20))->subMinutes(rand(5, 120));
                $submittedAt = (clone $startedAt)->addMinutes(rand(3, $quiz->time_limit ?? 20));

                $attempt = Attempt::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->id,
                    'status' => 'submitted',
                    'started_at' => $startedAt,
                    'submitted_at' => $submittedAt,
                    'total_marks' => $totalMarks,
                    'score' => 0,
                ]);

                $scoreObtained = 0;
                foreach ($quizQuestions as $question) {
                    $isCorrect = rand(1, 100) <= 65; // ~65% correctness bias
                    $correctOption = $question->options->firstWhere('is_correct', true);
                    $chosenOption = $isCorrect
                        ? $correctOption
                        : $question->options->where('is_correct', false)->random();
                    $marksObtained = $isCorrect ? $question->marks : 0;
                    $scoreObtained += $marksObtained;

                    Answer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'option_id' => $chosenOption?->id,
                        'answer_text' => null,
                        'is_correct' => $isCorrect,
                        'marks_obtained' => $marksObtained,
                    ]);
                }

                $attempt->update(['score' => $scoreObtained]);
            }

            if ($quiz->status === 'active' && rand(0, 4) === 0 && $eligibleStudents->isNotEmpty()) {
                Attempt::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $eligibleStudents->random()->id,
                    'status' => 'in_progress',
                    'started_at' => now()->subMinutes(rand(1, 10)),
                    'submitted_at' => null,
                    'total_marks' => $totalMarks,
                    'score' => null,
                ]);
            }
        }
        $this->command?->info('Seeded attempts and answers.');

        // ---------------------------------------------------------------
        // 6. BOOKMARKS
        // ---------------------------------------------------------------
        foreach ($activeStudents as $student) {
            $publicQuizzes = $allQuizzes->where('visibility', 'public');
            if ($publicQuizzes->isEmpty()) {
                continue;
            }
            $bookmarkQuizzes = $publicQuizzes->random(min(3, $publicQuizzes->count()));
            if (!$bookmarkQuizzes instanceof \Illuminate\Support\Collection) {
                $bookmarkQuizzes = collect([$bookmarkQuizzes]);
            }
            foreach ($bookmarkQuizzes as $quiz) {
                Bookmark::firstOrCreate([
                    'student_id' => $student->id,
                    'quiz_id' => $quiz->id,
                ]);
            }
        }
        $this->command?->info('Seeded bookmarks.');

        // ---------------------------------------------------------------
        // 7. ANNOUNCEMENTS + read tracking
        // ---------------------------------------------------------------
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $announcements = collect([
                ['title' => 'Welcome to Quizora', 'body' => 'The new semester quiz schedule is now live. Please check your dashboard.', 'audience' => 'all', 'type' => 'info'],
                ['title' => 'Scheduled Maintenance', 'body' => 'The platform will be briefly unavailable this weekend for maintenance.', 'audience' => 'all', 'type' => 'warning'],
                ['title' => 'New Question Bank Feature', 'body' => 'Teachers can now bulk import questions via CSV from the Question Bank page.', 'audience' => 'teachers', 'type' => 'success'],
                ['title' => 'Exam Season Reminder', 'body' => 'Make sure to attempt your assigned quizzes before the deadline.', 'audience' => 'students', 'type' => 'info'],
            ])->map(function ($a) use ($admin) {
                return Announcement::create($a + ['is_active' => true, 'created_by' => $admin->id]);
            });

            $allUsers = $teachers->merge($students);
            foreach ($announcements as $announcement) {
                foreach ($allUsers->random(rand(5, $allUsers->count())) as $user) {
                    AnnouncementRead::firstOrCreate([
                        'user_id' => $user->id,
                        'announcement_id' => $announcement->id,
                    ], ['read_at' => now()->subDays(rand(0, 10))]);
                }
            }
        }
        $this->command?->info('Seeded announcements.');

        // ---------------------------------------------------------------
        // 8. NOTIFICATIONS
        // ---------------------------------------------------------------
        $allUsers = $teachers->merge($students);
        foreach ($allUsers as $user) {
            $notifCount = rand(1, 4);
            for ($i = 0; $i < $notifCount; $i++) {
                AppNotification::create([
                    'user_id' => $user->id,
                    'type' => ['quiz', 'result', 'system'][array_rand([0, 1, 2])],
                    'title' => ['New quiz available', 'Your result is ready', 'Account update'][array_rand([0, 1, 2])],
                    'body' => 'This is a sample notification generated for demo purposes.',
                    'link' => null,
                    'read_at' => rand(0, 1) ? now()->subDays(rand(0, 5)) : null,
                ]);
            }
        }
        $this->command?->info('Seeded notifications.');

        $this->command?->info('Demo data seeding complete.');
    }
}
