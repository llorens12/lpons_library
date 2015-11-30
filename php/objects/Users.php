<?php
include_once "../styles/Template.php";
include_once "../abstracts/Controller.php";

session_cache_limiter ('nocache,private');
session_start();

if(!isset($_SESSION['user'],$_SESSION['typeUser'],$_SESSION['name'],$_SESSION['home']))
    header('Location: ../../index.php?error=Please start login');


class User extends Template{

    public function __construct($nameUser = "", $emailUser = "Anonimous", $typeUser = "", $home = "index.php", $sid = "", $currentOptionMenu = "")
    {
            parent::__construct($nameUser,      $emailUser,               $typeUser,      $home,               $sid,      $currentOptionMenu);


        if($typeUser == "librarian" || $typeUser == "admin")
            $this->permission = true;
        else
            $this->permission = false;
    }

    public function show(){

    }

    public function add(){

    }

    public function delete(){

    }

    public function update(){

    }
}

/*$control = new Controller();
$data = $control->select("select * from users");
$p = new User($_SESSION['name'],$_SESSION['user'],$_SESSION['typeUser'],$_SESSION['home'],SID,"Users");
$p->showTable($data);
echo $p;


function texto(){
    return '
    <p>Muy lejos, m�s all� de las monta�as de palabras, alejados de los pa�ses de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la sem�ntica, un gran oc�ano de lenguas. Un riachuelo llamado Pons fluye por su pueblo y los abastece con las normas necesarias. Hablamos de un pa�s paraisom�tico en el que a uno le caen pedazos de frases asadas en la boca. Ni siquiera los todopoderosos signos de puntuaci�n dominan a los textos simulados; una vida, se puede decir, poco ortogr�fica. Pero un buen d�a, una peque�a l�nea de texto simulado, llamada Lorem Ipsum, decidi� aventurarse y salir al vasto mundo de la gram�tica. El gran Oxmox le desanconsej� hacerlo, ya que esas tierras estaban llenas de comas malvadas, signos de interrogaci�n salvajes y puntos y coma traicioneros, pero el texto simulado no se dej� atemorizar. Empac� sus siete versales, enfund� su inicial en el cintur�n y se puso en camino.</p>

<p>Cuando ya hab�a escalado las primeras colinas de las monta�as cursivas, se dio media vuelta para dirigir su mirada por �ltima vez, hacia su ciudad natal Letralandia, el encabezamiento del pueblo Alfabeto y el subt�tulo de su propia calle, la calle del rengl�n. Una pregunta ret�rica se le pas� por la mente y le puso melanc�lico, pero enseguida reemprendi� su marcha. De nuevo en camino, se encontr� con una copia. La copia advirti� al peque�o texto simulado de que en el lugar del que ella ven�a, la hab�an reescrito miles de veces y que todo lo que hab�a quedado de su original era la palabra "y", as� que m�s le val�a al peque�o texto simulado volver a su pa�s, donde estar�a mucho m�s seguro. Pero nada de lo dicho por la copia pudo convencerlo, de manera que al cabo de poco tiempo, unos p�rfidos redactores publicitarios lo encontraron y emborracharon con Longe y Parole para llev�rselo despu�s a su agencia, donde abusaron de �l para sus proyectos, una y otra vez. Y si a�n no lo han reescrito, lo siguen utilizando hasta ahora. Muy lejos, m�s all� de las monta�as de palabras, alejados de los pa�ses de las vocales y las consonantes, viven los textos simulados.</p>

<p>Viven aislados en casas de letras, en la costa de la sem�ntica, un gran oc�ano de lenguas. Un riachuelo llamado Pons fluye por su pueblo y los abastece con las normas necesarias. Hablamos de un pa�s paraisom�tico en el que a uno le caen pedazos de frases asadas en la boca. Ni siquiera los todopoderosos signos de puntuaci�n dominan a los textos simulados; una vida, se puede decir, poco ortogr�fica. Pero un buen d�a, una peque�a l�nea de texto simulado, llamada Lorem Ipsum, decidi� aventurarse y salir al vasto mundo de la gram�tica. El gran Oxmox le desanconsej� hacerlo, ya que esas tierras estaban llenas de comas malvadas, signos de interrogaci�n salvajes y puntos y coma traicioneros, pero el texto simulado no se dej� atemorizar. Empac� sus siete versales, enfund� su inicial en el cintur�n y se puso en camino. Cuando ya hab�a escalado las primeras colinas de las monta�as cursivas, se dio media vuelta para dirigir su mirada por �ltima vez, hacia su ciudad natal Letralandia, el encabezamiento del pueblo Alfabeto y el subt�tulo de su propia calle, la calle del rengl�n. Una pregunta ret�rica se le pas� por la mente y le puso melanc�lico, pero enseguida reemprendi� su marcha.</p>

<p>De nuevo en camino, se encontr� con una copia. La copia advirti� al peque�o texto simulado de que en el lugar del que ella ven�a, la hab�an reescrito miles de veces y que todo lo que hab�a quedado de su original era la palabra "y", as� que m�s le val�a al peque�o texto simulado volver a su pa�s, donde estar�a mucho m�s seguro. Pero nada de lo dicho por la copia pudo convencerlo, de manera que al cabo de poco tiempo, unos p�rfidos redactores publicitarios lo encontraron y emborracharon con Longe y Parole para llev�rselo despu�s a su agencia, donde abusaron de �l para sus proyectos, una y otra vez. Y si a�n no lo han reescrito, lo siguen utilizando hasta ahora. Muy lejos, m�s all� de las monta�as de palabras, alejados de los pa�ses de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la sem�ntica, un gran oc�ano de lenguas. Un riachuelo llamado Pons fluye por su pueblo y los abastece con las normas necesarias. Hablamos de un pa�s paraisom�tico en el que a uno le caen pedazos de frases asadas en la boca. Ni siquiera los todopoderosos signos de puntuaci�n dominan a los textos simulados; una vida, se puede decir, poco ortogr�fica.</p>

<p>Pero un buen d�a, una peque�a l�nea de texto simulado, llamada Lorem Ipsum, decidi� aventurarse y salir al vasto mundo de la gram�tica. El gran Oxmox le desanconsej� hacerlo, ya que esas tierras estaban llenas de comas malvadas, signos de interrogaci�n salvajes y puntos y coma traicioneros, pero el texto simulado no se dej� atemorizar. Empac� sus siete versales, enfund� su inicial en el cintur�n y se puso en camino. Cuando ya hab�a escalado las primeras colinas de las monta�as cursivas, se dio media vuelta para dirigir su mirada por �ltima vez, hacia su ciudad natal Letralandia, el encabezamiento del pueblo Alfabeto y el subt�tulo de su propia calle, la calle del rengl�n. Una pregunta ret�rica se le pas� por la mente y le puso melanc�lico, pero enseguida reemprendi� su marcha. De nuevo en camino, se encontr� con una copia. La copia advirti� al peque�o texto simulado de que en el lugar del que ella ven�a, la hab�an reescrito miles de veces y que todo lo que hab�a quedado de su original era la palabra "y", as� que m�s le val�a al peque�o texto simulado volver a su pa�s, donde estar�a mucho m�s seguro.</p>

<p>Pero nada de lo dicho por la copia pudo convencerlo, de manera que al cabo de poco tiempo, unos p�rfidos redactores publicitarios lo encontraron y emborracharon con Longe y Parole para llev�rselo despu�s a su agencia, donde abusaron de �l para sus proyectos, una y otra vez. Y si a�n no lo han reescrito, lo siguen utilizando hasta ahora. Muy lejos, m�s all� de las monta�as de palabras, alejados de los pa�ses de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la sem�ntica, un gran oc�ano de lenguas. Un riachuelo llamado Pons fluye por su pueblo y los abastece con las normas necesarias. Hablamos de un pa�s paraisom�tico en el que a uno le caen pedazos de frases asadas en la boca. Ni siquiera los todopoderosos signos de puntuaci�n dominan a los textos simulados; una vida, se puede decir, poco ortogr�fica. Pero un buen d�a, una peque�a l�nea de texto simulado, llamada Lorem Ipsum, decidi� aventurarse y salir al vasto mundo de la gram�tica. El gran Oxmox le desanconsej� hacerlo, ya que esas tierras estaban llenas de comas malvadas, signos de interrogaci�n salvajes y puntos y coma traicioneros, pero el texto simulado no se dej� atemorizar.</p>

<p>Empac� sus siete versales, enfund� su inicial en el cintur�n y se puso en camino. Cuando ya hab�a escalado las primeras colinas de las monta�as cursivas, se dio media vuelta para dirigir su mirada por �ltima vez, hacia su ciudad natal Letralandia, el encabezamiento del pueblo Alfabeto y el subt�tulo de su propia calle, la calle del rengl�n. Una pregunta ret�rica se le pas� por la mente y le puso melanc�lico, pero enseguida reemprendi� su marcha. De nuevo en camino, se encontr� con una copia. La copia advirti� al peque�o texto simulado de que en el lugar del que ella ven�a, la hab�an reescrito miles de veces y que todo lo que hab�a quedado de su original era la palabra "y", as� que m�s le val�a al peque�o texto simulado volver a su pa�s, donde estar�a mucho m�s seguro. Pero nada de lo dicho por la copia pudo convencerlo, de manera que al cabo de poco tiempo, unos p�rfidos redactores publicitarios lo encontraron y emborracharon con Longe y Parole para llev�rselo despu�s a su agencia, donde abusaron de �l para sus proyectos, una y otra vez. Y si a�n no lo han reescrito, lo siguen utilizando hasta ahora. Muy lejos, m�s all� de las monta�as de palabras, alejados de los pa�ses de las vocales y las consonantes, viven los textos simulados.</p>

<p>Viven aislados en casas de letras, en la costa de la sem�ntica, un gran oc�ano de lenguas. Un riachuelo llamado Pons fluye por su pueblo y los abastece con las normas necesarias. Hablamos de un pa�s paraisom�tico en el que a uno le caen pedazos de frases asadas en la boca. Ni siquiera los todopoderosos signos de puntuaci�n dominan a los textos simulados; una vida, se puede decir, poco ortogr�fica. Pero un buen d�a, una peque�a l�nea de texto simulado, llamada Lorem Ipsum, decidi� aventurarse y salir al vasto mundo de la gram�tica. El gran Oxmox le desanconsej� hacerlo, ya que esas tierras estaban llenas de comas malvadas, signos de interrogaci�n salvajes y puntos y coma traicioneros, pero el texto simulado no se dej� atemorizar. Empac� sus siete versales, enfund� su inicial en el cintur�n y se puso en camino. Cuando ya hab�a escalado las primeras colinas de las monta�as cursivas, se dio media vuelta para dirigir su mirada por �ltima vez, hacia su ciudad natal Letralandia, el encabezamiento del pueblo Alfabeto y el subt�tulo de su propia calle, la calle del rengl�n. Una pregunta ret�rica se le pas� por la mente y le puso melanc�lico, pero enseguida reemprendi� su marcha. De nuevo en camino, se encontr� con una copia. La copia advirti� al peque�o texto simulado de que en el lugar del que ella ven�a, la hab�an reescrito miles de veces y que todo lo que hab�a quedado de su original era la palabra "y", as� que m�s le val�a al peque�o texto simulado volver a su pa�s, donde estar�a mucho m�s seguro. Pero nada de lo dicho por la copia pudo convencerlo, de manera que al cabo de poco tiempo, unos p�rfidos redactores publicitarios lo encontraron y emborracharon con Longe y Parole para llev�rselo despu�s a su agencia, donde abusaron de �l para sus proyectos, una y otra vez. Y si a�n no lo han reescrito, lo siguen utilizando hasta ahora. Muy lejos, m�s all� de las monta�as de palabras, alejados de los pa�ses de las vocales y las consonantes, viven los textos simulados. Viven aislados en casas de letras, en la costa de la sem�ntica, un gran oc�ano de lenguas. Un riachuelo llamado Pons fluye por su pueblo y los abastece con las normas necesarias. Hablamos de un pa�s paraisom�tico en el que a uno le caen pedazos de frases</p>
    ';
}
?>