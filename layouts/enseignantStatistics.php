<?php

include_once __DIR__."/../src/Models/Cour.php";
include_once __DIR__."/../src/Models/Enseignant.php";

$id_enseignant = $_SESSION['user_id'] ?? null;

$enseignant = new Enseignant();
$coursCount = $enseignant->getEnseignantCoursCount($id_enseignant);
$inscriptionsCount = $enseignant->getCoursInscriptions($id_enseignant)->num_rows;

?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 my-10">

    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Cours</h3>
            <p class="text-2xl font-bold text-yellow-600"><?php echo $coursCount?></p>
        </div>
        <div class="p-4 bg-yellow-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-yellow-600" viewBox="0 0 512 512">
                <path d="M168 80c-13.3 0-24 10.7-24 24l0 304c0 8.4-1.4 16.5-4.1 24L440 432c13.3 0 24-10.7 24-24l0-304c0-13.3-10.7-24-24-24L168 80zM72 480c-39.8 0-72-32.2-72-72L0 112C0 98.7 10.7 88 24 88s24 10.7 24 24l0 296c0 13.3 10.7 24 24 24s24-10.7 24-24l0-304c0-39.8 32.2-72 72-72l272 0c39.8 0 72 32.2 72 72l0 304c0 39.8-32.2 72-72 72L72 480zM176 136c0-13.3 10.7-24 24-24l96 0c13.3 0 24 10.7 24 24l0 80c0 13.3-10.7 24-24 24l-96 0c-13.3 0-24-10.7-24-24l0-80zm200-24l32 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-32 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zm0 80l32 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-32 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zM200 272l208 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-208 0c-13.3 0-24-10.7-24-24s10.7-24 24-24zm0 80l208 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-208 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
            </svg>
        </div>
    </div>


    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Inscriptions</h3>
            <p class="text-2xl font-bold text-red-600"><?php echo $inscriptionsCount?></p>
        </div>
        <div class="p-4 bg-red-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-red-600" viewBox="0 0 512 512">
                <path d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152L0 424c0 48.6 39.4 88 88 88l272 0c48.6 0 88-39.4 88-88l0-112c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 112c0 22.1-17.9 40-40 40L88 464c-22.1 0-40-17.9-40-40l0-272c0-22.1 17.9-40 40-40l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L88 64z"/>
            </svg>
        </div>
    </div>

</div>