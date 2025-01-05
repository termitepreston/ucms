<?php

$names = [
    "James",
    "Mary",
    "John",
    "Patricia",
    "Robert",
    "Jennifer",
    "Michael",
    "Linda",
    "William",
    "Elizabeth",
    "David",
    "Barbara",
    "Richard",
    "Susan",
    "Joseph",
    "Jessica",
    "Thomas",
    "Sarah",
    "Charles",
    "Karen",
    "Christopher",
    "Nancy",
    "Daniel",
    "Lisa",
    "Matthew",
    "Margaret",
    "Anthony",
    "Betty",
    "Mark",
    "Sandra",
    "Donald",
    "Ashley",
    "Steven",
    "Kimberly",
    "Paul",
    "Emily",
    "Andrew",
    "Donna",
    "Joshua",
    "Michelle",
    "Kenneth",
    "Carol",
    "Kevin",
    "Amanda",
    "Brian",
    "Melissa",
    "George",
    "Deborah",
    "Timothy",
    "Stephanie",
    "Ronald",
    "Rebecca",
    "Edward",
    "Laura",
    "Jason",
    "Sharon",
    "Jeffrey",
    "Cynthia",
    "Ryan",
    "Kathleen",
    "Jacob",
    "Amy",
    "Gary",
    "Angela",
    "Nicholas",
    "Shirley",
    "Eric",
    "Anna",
    "Jonathan",
    "Brenda",
    "Stephen",
    "Pamela",
    "Larry",
    "Emma",
    "Justin",
    "Nicole",
    "Scott",
    "Helen",
    "Brandon",
    "Samantha",
    "Benjamin",
    "Katherine",
    "Samuel",
    "Christine",
    "Frank",
    "Debra",
    "Gregory",
    "Rachel",
    "Raymond",
    "Carolyn",
    "Alexander",
    "Janet",
    "Patrick",
    "Maria",
    "Jack",
    "Heather",
    "Dennis",
    "Diane",
    "Jerry",
    "Ruth"
];

print 'Hello from php' . '<br/>';

print 'Let\'s familiarize ourselves with the basics of php.' . '<br/>';


// there are two types arrays in php:
// simple, sequentially indexed arrays,
// and key, value 'arrays' or dicts as 
// we call them in other languages.

$arr1 = [
    2,
    3,
    4,
    5,
];

$arr2 = [
    0 => rand(0, 300),
    1 => "is",
    2 => 3,
    3 => phpversion(),
];

$arr3 = [
    0 => phpversion(),
    0 => new \DateTimeImmutable("now"),
];

// Array 4 has a 'hole' thus it cannot considered a list.
$arr4 = [
    0 => $names[10],
    2 => $names[13],
];


$idGrades = [
    2025001 => 'A',
    'B',
    'C',
    'D',
    'F',
    'Gap' => phpversion(),
    'RA'
];

$twoDArr = [
    42 => [2, 3, 5, 7],
    [phpversion(), "42"],
    [7, 666]
];

?>

<pre>
    <?php for ($i = 0; $i < count($arr2); $i++) { ?>
        <code><?php print $arr2[$i] ?></code>
    <?php } ?>
</pre>

<?php

// we can check if a collection is a simple list using array_is_list().

print 'is $arr1 a list? ' . (array_is_list($arr1) ? 'Yes' : 'No') . '.<br/>';
print 'is $arr2 a list? ' . (array_is_list($arr2) ? 'Yes' : 'No') . '.<br/>';
print 'is $arr3 a list? ' . (array_is_list($arr3) ? 'Yes' : 'No') . '.<br/>';
print 'is $arr4 a list? ' . (array_is_list($arr4) ? 'Yes' : 'No') . '.<br/>';
print 'is $idGrades a list? ' . (array_is_list($idGrades) ? 'Yes' : 'No') . '.<br/>';
print 'is $twoDArr a list? ' . (array_is_list($twoDArr) ? 'Yes' : 'No') . '.<br/>';


foreach ($idGrades as $id => $grade) {
    print "\$idGrades[$id] = " . $idGrades[$id] . '<br/>';
}

var_dump($arr1, $arr2, $arr3, $arr4);

// We can include files using require_once directive.
require_once __DIR__ . '/funs.php';

$dave = new Person("David", "Durst", 32, 55000.50);
print '<br/>' . $dave->greet() . '<br/>';

// we can destructure arrays.

$rainFall_mm = [20, 18, 25];

[$jan, $fm] = $rainFall_mm;

var_dump($jan, $fm);

// php also has functional style collection transformations using anonymous functions.
// for example, if we want to square the first 10 primes, we can do:

$primes = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29,];

$primesSquared = array_map(fn($p) => $p * $p, $primes);

print '<br/>';
var_dump($primes, $primesSquared);
print '<br/>';
