// Author Notes:
// This file has been translated from English to Romanian by OptimuS, StormZone & Dorin2oo7 from www.amxbans.de.

// This is the Installation Language File

<?php
//encoding and locale
define("_ENCODING","ISO-8859-2"); //ISO-8859-1,utf-8

define("_INSTALLATION","Instalare");
define("_STEP","Pasul");
define("_STEP1","Start");
define("_STEP2","Informaþii");
define("_STEP3","Directoare");
define("_STEP4","Baza de Date");
define("_STEP5","Administrator");
define("_STEP6","Sumar");
define("_STEP7","Terminare");

define("_OF","din");
define("_NEXT","înainte");
define("_BACK","înapoi");
define("_DIRCHECK","Verificarea Directorului");

//step1
define("_WELCOME","Bine ai venit la suita de instalare din cadrul AMXBans.");
define("_WELCOME2","Cu ajutorul ei vei fi condus prin procedura de instalare.");
define("_LICENSEAGREE","Accept acordul de licenþã");

//step2
define("_STEP2DESC","Informaþii despre setãrile serverului");
define("_SERVERSETUP","Setãrile serverului");
define("_REFRESH","Reîncarcã");
define("_VERSION","Versiune");
define("_ON","Pornit");
define("_OFF","Oprit");
define("_SEC","Secunde");
define("_SETRECOMMENDED","Setãrile intrunesc recomandãrile.");
define("_SETNOTRECOMMENDED","Nu sunt recomandate, dar ar trebui sã functioneze corespunzãtor.");

//step3
define("_STEP3DESC","Iau informaþii despre directoare");
define("_STEP3DESC2","In mod normal, scriptul detecteazã calea automat");
define("_DIRROOT","Calea directorului instalãrii");
define("_DIRDOCUMENT","Calea");
define("_RECHECK","Re-verificã");
define("_ROOTDIRS","Directoarele Serverului");
define("_OK","OK");
define("_NOTWRITABLE","Directorul nu este inscriptibil, verificã privilegiile!");
define("_SETUPNOTDELETABLE","Fiºierul setup.php trebuie sã fie ºters manual dupã instalare!");

//step4
define("_STEP4DESC","Iau informaþiile despre Baza de Date");
define("_DBSETTINGS","Setãrile bazei de date");
define("_DBCHECK","Verific datele de acces");
define("_CANTCONNECT","Datele de acces sunt eronate!");
define("_CANTSELECTDB","Baza de date nu a fost gãsitã!");
define("_DBOK","Accesul la baza de date este corespunzator!");
define("_DBPREVILEGES","Privilegiile utilizatorului Bazei de Date");
define("_HOST","Server");
define("_USER","Nume Utilizator");
define("_PASSWORD","Parolã");
define("_DATABASE","Baza de Date");
define("_TBLPREFIX","Prefixul tabelului");
define("_NOTALLPREVILEGES","Utilizatorul nu are toate privilegiile necesare!");
define("_PREFIXEXISTSV5","O instalare existentã s-a descoperit, nu se poate actualiza!");
define("_PREFIXEXISTSV6","O instalare existentã s-a descoperit, se va actualiza!");

//step5
define("_STEP5DESC","Crearea primului Administrator web");
define("_ADMINSETTINGS","Datele de acces ale Administratorului");
define("_PASSWORD2","Rescrie parolã");
define("_EMAILADR","Adresã de e-mail");
define("_ADMINCHECK","Verificã datele");
define("_PWNOCONFIRM","Parolele nu se potrivesc!");
define("_NOREQUIREDFIELDS","Câmpuri necesare lipsesc!");
define("_ADMINOK","Datele de admin sunt în regulã!");
define("_USERTOSHORT","Nume de utilizator prea scurt (min. 4 caractere)!");
define("_PWTOSHORT","Parolã prea scurtã (min. 4 caractere)!");
define("_NOVALIDEMAIL","Adersa de email nu este validã!");

//step6
define("_STEP6DESC","Sumarul informaþiilor colectate");
define("_STEP6DESC2","AMXBans va fi instalat pe baza urmãtoarelor informaþii");

//step7
define("_STEP7DESC","Progresul instalãrii");
define("_TABLECREATE","Crearea structurii tabelului");
define("_DEFAULTDATACREATE","Introdu datele necesare");
define("_DEFAULTWEBSETTINGSCREATE","Introdu setãrile");
define("_DEFAULTUSERMENUCREATE","Seteazã meniul utilizatorului");
define("_DEFAULTMODULESCREATE","Instaleazã modul");
define("_WEBADMINCREATE","Creeazã admin web");
define("_ALREADYEXISTS","Existã deja");
define("_CREATED","Creat cu succes");
define("_FAILED","Eºuat");
define("_INSERTED","înregistrat cu succes");
define("_CREATEWEBADMIN","Date admin web");
define("_CREATEUSERLEVEL","Nivel admin web");
define("_CREATEWEBSETTINGS","Site");
define("_CREATEUSERMENU","Meniu utilizator");
define("_FILEEXISTS","Configul existã deja!");
define("_FILEOPENERROR","Configul nu a putut fi creat!");
define("_FILESUCCESS","Config creat cu succes!");
define("_MANUALEDIT","Deschide /include/db.config.inc.php ºi prin copy / paste scrie asta:");
define("_SETUPENDDESC","Fiºierul setup.php va fi acum ºters ºi vei fi redirectat cãtre AMXBans!");
define("_SETUPEND","Mergi la AMXBans...");
?>