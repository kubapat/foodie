<?php
/*
  FILE: src/content/langs.php
  DESCRIPTION: This file contains predefined texts & responses to enable i18n

  Methods:
   - detectLanguage() - returns code of user's language ("pl" or "en")
   - updateLanguage() - returns validated updated value of language selected by user
  Variables:
   - languages (array typed) - It's an array storing list of all available languages in system - including shortcut (e.g. pl - Polish) and full name
   - translations (array typed) - holds all translations for website, 1st dimension corresponds to language whereas 2nd to specific page or function

   **********************************************************************
   *   $translations[LANGUAGE][WEBPAGE OR FUNCTION CALLSIGN] = array(   *
   *    "DATA" => "VALUE"                                               *
   *   );                                                               *
   **********************************************************************
*/

  class Langs {

     public function detectLanguage() {
        if(isset($_SESSION['lang'])) return $_SESSION['lang'];
        $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

        if(Utilities::strContains($language,"en")) return "en";
        else return "en";
     }

     public function updateLanguage() {
        global $conn;
        $that = get_called_class();
        $found = false;

        foreach($that::languages as $key => $value) {
           if($key == $_GET['lang']) {
               $found = true;
               break;
           }
        }

        if(!$found) {
           $_SESSION['error']    = $that::translations[$_GET['lang']]["errors"]["langNotFound"];
           $_SESSION['errcolor'] = "error";
           return $_SESSION['lang'];
        } else {
           if($_GET['lang'] != $_SESSION['lang']) { //To omit pop-up when $_GET parameter is present
              $_SESSION['error']    = $that::translations[$_GET['lang']]["errors"]["langChanged"];
              $_SESSION['errcolor'] = "success";
              if($_SESSION['logged']) {
                 mysqli_query($conn,"UPDATE users SET language='".addslashes($_GET['lang'])."' WHERE login='".addslashes($_SESSION['login'])."';");
              }
           }
           return addslashes($_GET['lang']);
        }
     }


     public const languages = array(
        "en" => "English",
        "pl" => "Polski"
     );

     public const translations = array(
        "pl" => array(
                "errors" => array(
                   "loginToSee"    => "Zaloguj się, aby zobaczyć tę stronę!",
                   "noPrivs"       => "Nie masz wystarczających uprawnień!",
                   "badRequest"    => "Niewłaściwy parametr żądania",
                   "langNotFound"  => "Podany język nie istnieje!",
                   "langChanged"   => "Pomyślnie zmieniono język",
                   "wrongLogin"    => "Podano błędne dane!",
                   "accBanned"     => "Podane konto jest zbanowane!",
                   "notActive"     => "Podane konto nie zostało zaaktywowane! Sprawdź swoją skrzynkę mailową!",
                   "loggedIn"      => "Pomyślnie zalogowano!",
                   "confirm"       => "Potwierdż swoją decyzję",
                   "annNotExists"  => "Podane ogłoszenie nie istnieje!",
                   "annDeleted"    => "Pomyślnie usunięto ogłoszenie",
                   "annEdited"     => "Pomyślnie zedytowano ogłoszenie",
                   "annAdded"      => "Pomyślnie dodano ogłoszenie",
                   "threadAdded"   => "Pomyślnie dodano nowy wątek",
                   "threadRemoved" => "Pomyślnie usunięto wątek/post",
                   "rangeAnnTitle" => "Długość tytułu powinna się mieścić w przedziale od 1 do 100",
                   "rangeAnnText"  => "Treść nie może być pusta",
                   "invalidDate"   => "Podana data jest nieprawidłowa",
                   "invalidImage"  => "Podane zdjęcie jest nieprawidłowe",
                   "thrNotExists"  => "Ten wątek nie istnieje",
                   "invalidQuant"  => "Niewłaściwa ilość",
                   "invalidStatus" => "Niewłaściwy status",
                   "invalidName"   => "Długość imienia/nazwy firmy powinna się mieścić w przedziale od 1 do 100",
                   "invalidEmail"  => "Niewłaściwy E-mail",
                   "invalidLoc"    => "Niewłaściwa lokalizacja",
                   "passNotSame"   => "Podane hasła nie są takie same",
                   "passInvalid"   => "Podane hasło jest niewłaściwe",
                   "passChanged"   => "Pomyślnie zmieniono hasło! Zaloguj się używając nowych danych!",
                   "dataUpdated"   => "Pomyślnie zaktualizowano dane",
                   "invalidLogin"  => "Długość loginu powinna się mieścić w przedziale od 1 do 100",
                   "loginUsed"     => "Ten login jest już w użyciu",
                   "emailUsed"     => "Ten E-mail jest już w użyciu",
                   "registered"    => "Pomyślnie zarejestrowano się",
                   "userNotFound"  => "Podany user nie isntnieje!",
                   "messSent"      => "Pomyślnie wysłano wiadomość!",
                   "messNotExists" => "Ta wiadomość nie istnieje!",
                   "sameRecipient" => "Nie możesz przekazać sobie tych produktów!",
                   "forwarded"     => "Pomyślnie przekazano jedzenie, musisz teraz oczekiwać na potwierdzenie od odbiorcy by zakończyć proces!",
                   "opsEdited"     => "Pomyślnie wykonano wybrane operacje",
                   "privsEdited"   => "Pomyślnie zmieniono uprawnienia użytkownika",
                   "banEdited"     => "Pomyślnie zmieniono status Ban użytkownika"
                ),

                "menu" => array(
                   "signOut" => "Wyloguj się",
                   "home"    => "Strona Główna",
                   "inbox"   => "Wiadomości",
                   "getFood" => "Pozyskaj żywność",
                   "map"     => "Mapa",
                   "list"    => "Lista",
                   "myFood"  => "Moja żywność",
                   "ranks"   => "Ranking",
                   "forum"   => "Forum",
                   "info"    => "Pomoc",
                   "admin"   => "Administracja"
                ),

                "header" => array(
                   "allNotifications" => "Zobacz wszystkie",
                   "newNotifications" => "Nowe Powiadomienia:",
                   "language"         => "Język",
                   "profile"          => "Moje Konto",
                   "settings"         => "Ustawienia",
                   "selectLang"       => "Wybierz język",
                   "allNotifs"        => "Wszystkie powiadomienia"
                ),

                "login" => array(
                   "PageTitle"    => "Logowanie do Foodie",
                   "loginTitle"   => "Login lub E-mail",
                   "PasswdTitle"  => "Hasło",
                   "LoginBtn"     => "Zaloguj się",
                   "forgotPasswd" => "Zapomniałeś/aś hasła?",
                   "notAccount"   => "Nie masz konta?",
                   "register"     => "Zarejestruj się"
                ),

                "home" => array(
                   "annTitle"  => "Ogłoszenia",
                   "noAnns"    => "Nie ma jeszcze żadnych ogłoszeń",
                   "AnnImgAlt" => "Grafika ogłoszenia",
                   "AnnBy"     => "Dodano przez",
                   "editAnn"   => "Edytuj",
                   "addAnn"    => "Dodaj",
                   "delAnn"    => "Usuń",
                   "title"     => "Tytuł",
                   "pubDate"   => "Data Publikacji",
                   "text"      => "Treść ogłoszenia",
                   "newAnn"    => "Nowe ogłoszenie",
                   "image"     => "Zdjęcie",
                   "statsOne"  => "Posiadane produkty",
                   "statsTwo"  => "Wystawione produkty",
                   "statsThr"  => "Produkty do potwierdzenia odbioru"
                ),

                "forum" => array(
                   "newThread"   => "Nowy wątek",
                   "threadsList" => "Lista wątków",
                   "newPost"     => "Nowy Post",
                   "lastActive"  => "Ostatnio aktywny(a)",
                   "noThreads"   => "Brak dostępnych wątków",
                   "tableTitle"  => "Tytuł",
                   "tableLast"   => "Ostatni post",
                   "tableAuthor" => "Autor",
                   "tableReply"  => "Odpowiedzi",
                   "threadText"  => "Treść",
                   "threadBy"    => "Dodano",
                   "byWord"      => "przez",
                   "never"       => "nigdy",
                   "beFirst"     => "Nie ma jeszcze żadnych odpowiedzi, bądż pierwszym/ą!"
                ),

                "storage" => array(
                   "newFood"     => "Nowy produkt",
                   "name"        => "Nazwa produktu",
                   "quantity"    => "Ilość",
                   "bestBefore"  => "Najlepiej spożyć przed (jeśli jest podane)",
                   "description" => "Opis (nieobowiązkowy)",
                   "descrNote"   => "Możesz umieścić tutaj składniki, kaloryczność, informacje dla diabetyków i alergików, informacje o producencie, masę, ważne uwagi etc.",
                   "image"       => "Zdjęcie produktu (nieobowiązkowe)",
                   "status"      => "Status",
                   "statusDone"  => "Spożyte",
                   "statusHold"  => "Przechowywane",
                   "statusOpen"  => "Do wydania",
                   "owner"       => "Posiadacz",
                   "foodAdded"   => "Pomyślnie dodano nowy produkt",
                   "foodEdited"  => "Pomyślnie zedytowano produkty",
                   "noFood"      => "Nie ma w tej chwili żadnych produktów w posiadaniu",
                   "actions"     => "Akcje",
                   "myUsed"      => "Moje zużyte i przechowywane produkty",
                   "mapAvail"    => "Mapa użytkowników z dostępnymi produktami",
                   "clickToSee"  => "Kliknij, aby zobaczyć produkty użytkownika",
                   "GiveFood"    => "Przekaż jedzenie",
                   "giveInstr"   => "Zaznacz wszystkie produkty powyżej, które chcesz przekazać i wybierz odbiorcę poniżej",
                   "giveBtn"     => "Przekaż",
                   "notEdited"   => "Zmiany dla tego produktu nie zostaną zapisane, ponieważ został komuś przekazany i oczekuje na odebranie",
                   "myOps"       => "Moje bieżące operacje",
                   "product"     => "Produkt",
                   "from"        => "Od",
                   "for"         => "Dla",
                   "noOps"       => "Nie ma żadnych bieżących operacji",
                   "relation"    => "Relacja posiadania",
                   "execute"     => "Wykonaj",
                   "checkOps"    => "Odhaczenie operacji, która wychodzi o Ciebie spowoduje jej wycofanie, natomiast odhaczenie operacji przychodzącej spowoduje potwierdzenie odbioru"
                ),

                "profile" => array(
                   "name"     => "Imię i Nazwisko/Nazwa Firmy",
                   "email"    => "E-mail",
                   "photo"    => "Zdjęcie",
                   "location" => "Twoja lokalizacja",
                   "change"   => "Zmień",
                   "oldpass"  => "Stare hasło",
                   "newpass"  => "Nowe hasło",
                   "repeat"   => "Powtórz nowe hasło",
                   "changeIt" => "Zmień hasło",
                   "user"     => "Użytkownik"
                ),

                "register" => array(
                   "login"  => "Login",
                   "havAcc" => "Masz już konto?",
                   "agree"  => "Zgadzam się z regulaminem korzystania z serwisu"
                ),

                "mess" => array(
                   "NewMess"   => "Nowa wiadomość",
                   "recipient" => "Odbiorca",
                   "subject"   => "Temat",
                   "lastMess"  => "Ostatnia wiadomość",
                   "sender"    => "Nadawca",
                   "read"      => "Przeczytana",
                   "yes"       => "Tak",
                   "no"        => "Nie",
                   "noMess"    => "Brak wiadomości",
                   "messIn"    => "Ilość wiadomości",
                   "send"      => "Wyślij",
                   "inbox"     => "Odebrane",
                   "sent"      => "Wysłane",
                   "sendReq"   => "Ustal szczegóły odbioru przez wiadomość",
                   "foodInq"   => "Zapytanie o jedzenie"
                ),

                "notifications" => array(
                   "confirm"   => "Otrzymałeś/aś nowe produkty, potwierdź ich odebranie w zakładce Moja Żywność",
                   "confirmed" => "Użytkownik zaakaceptował twoje produkty",
                   "newMess"   => "Masz nową wiadomość"
                ),

                "ranks" => array(
                   "donorsRank" => "Ranking donatorów jedzenia",
                   "delivRank"  => "Ranking pośredników dostawach",
                   "place"      => "Miejsce"
                ),

                "info" => "<h5>Jak przekazać komuś jedzenie?</h5>Aby przekazać jedzenie, przejdź do zakładki Moja żywność, zaznacz produkty o statusie Do wydania i wybierz osobę, której chcesz je przekazać, następnie poproś odbiorcę o potwierdzenie odebrania poprzez zakładkę Moje operacje w Moja Żywność, należy zaznacz produkty do zatiwerdzenia, a następnie kliknąć wykonaj<br><br><h5>Jak pozyskać jedzenie?</h5>Przejdź do zakładki Pozyskaj żywność, wybierz widok poprzez mape lub listę, wybierz użytkownika poprzez mapę lub żywność poprzez listę, następnie wyślij wiadomość do posiadacza produktu ws. szczegółów przekazania produktu, a następnie oczekuj żywności od niego, którą następnie potwierdzisz w zakładce Moje operacje w Moja Żywność<br><br><h5>O co chodzi z dostawcami/pośrednikami w rankingu?</h5>Jeśli zależy Ci naprawdę na pomaganiu innym to możesz zostać pośrednikiem/dostawcą pomiędzy posiadaczem produktu, a docelowym odbiorcą np. sklepem, a Domem dziecka. Odbierasz wtedy normalnie od użytkownika towar, a następnie przekazujesz go także w standardowy sposób docelowemu odbiorcy i dostajesz za to premiowany w rankingu<br><br>Po więcej pytań zapraszam do kontaktu przez wiadomości z użytkownikiem Jakub Patałuch",

                "admin" => array(
                   "changePrivs" => "Zmień uprawnienia",
                   "unban"       => "Odbanuj",
                   "ban"         => "Zbanuj",
                   "privs"       => "Uprawnienia",
                   "registered"  => "Data rejestracji",
                   "ops"         => "Operacje",
                   "onlyAdmin"   => "Te operacje są zabronione, bo jesteś jedynym adminem"
                )
        ),

        "en" => array(
                "errors" => array(
                   "loginToSee"    => "Log in in order to see this page!",
                   "noPrivs"       => "No enough permissions!",
                   "badRequest"    => "Invalid Request parameter!",
                   "langNotFound"  => "Given language doesn't exist!",
                   "langChanged"   => "Language has been successfully changed",
                   "wrongLogin"    => "You've typed incorrect data!",
                   "accBanned"     => "Your account has been banned!",
                   "notActive"     => "This account is inactive! Check your mailbox!",
                   "loggedIn"      => "Successfully logged in!",
                   "confirm"       => "Confirm your decision",
                   "annNotExists"  => "This announcement doesn't exist",
                   "annDeleted"    => "Announcement has been successfully deleted",
                   "annEdited"     => "Announcement has been successfully edited",
                   "annAdded"      => "Announcement has been successfully added",
                   "threadAdded"   => "Thread/post has been successfully added",
                   "threadRemoved" => "Thread/post has been successfully removed",
                   "rangeAnnTitle" => "Title length should be between 1 and 100",
                   "rangeAnnText"  => "Text length cannot be zero",
                   "invalidDate"   => "Given date is invalid",
                   "invalidImage"  => "Given image is invalid",
                   "thrNotExists"  => "This thread/post doesn't exist",
                   "invalidQuant"  => "Invalid quantity",
                   "invalidStatus" => "Invalid status",
                   "invalidName"   => "Name/Company name should be between 1 and 100",
                   "invalidEmail"  => "Invalid E-mail",
                   "invalidLoc"    => "Invalid location",
                   "passNotSame"   => "Given passwords aren't the same",
                   "passInvalid"   => "Invalid password",
                   "passChanged"   => "Password has been successfully changed! Log into using new credentials!",
                   "dataUpdated"   => "Data have been successfully updated",
                   "invalidLogin"  => "Login length should be between 1 and 100",
                   "loginUsed"     => "This login is already used",
                   "emailUsed"     => "This E-mail is already used",
                   "registered"    => "You have successfully signed up!",
                   "userNotFound"  => "This user doesn't exist!",
                   "messSent"      => "Message has been sent successfully!",
                   "messNotExists" => "Message doesn't exist!",
                   "sameRecipient" => "It's impossible to give products to yourself!",
                   "forwarded"     => "Food has been successfully given, now you need to wait for receivment confirmation from recipient to complete operation!",
                   "opsEdited"     => "Successfully executed chosen operations!",
                   "privsEdited"   => "User privs have been successfully changed",
                   "banEdited"     => "User ban status has been successfully updated"
                ),

                "menu" => array(
                   "signOut" => "Sign out",
                   "home"    => "Home",
                   "inbox"   => "Inbox",
                   "getFood" => "Get Food",
                   "map"     => "Map",
                   "list"    => "List",
                   "myFood"  => "My Food",
                   "ranks"   => "Ranks",
                   "forum"   => "Forum",
                   "info"    => "Help",
                   "admin"   => "Admin"
                ),

                "header" => array(
                   "allNotifications" => "See all",
                   "newNotifications" => "New notifications:",
                   "language"         => "Language",
                   "profile"          => "My account",
                   "settings"         => "Settings",
                   "selectLang"       => "Choose your language",
                   "allNotifs"        => "All notifications"
                ),

                "login" => array(
                   "PageTitle"    => "Login into Foodie",
                   "loginTitle"   => "Login or E-mail",
                   "PasswdTitle"  => "Password",
                   "LoginBtn"     => "Log in",
                   "forgotPasswd" => "Forgotten password?",
                   "notAccount"   => "Don't you have an account?",
                   "register"     => "Sign up"
                ),

                "home" => array(
                   "annTitle"  => "Announcements",
                   "noAnns"    => "There aren't any announcements so far",
                   "AnnImgAlt" => "Announcement image",
                   "AnnBy"     => "Added by",
                   "editAnn"   => "Edit",
                   "addAnn"    => "Add",
                   "delAnn"    => "Delete",
                   "title"     => "Title",
                   "pubDate"   => "Date of publish",
                   "text"      => "Announcement text",
                   "newAnn"    => "New announcement",
                   "image"     => "Image",
                   "statsOne"  => "Owned products",
                   "statsTwo"  => "Listed products",
                   "statsThr"  => "Products to confirm receival"

                ),

                "forum" => array(
                   "newThread"   => "New thread",
                   "threadsList" => "Threads list",
                   "newPost"     => "New post",
                   "lastActive"  => "Last active",
                   "noThreads"   => "There aren't any available threads at this moment",
                   "tableTitle"  => "Title",
                   "tableLast"   => "Last post",
                   "tableAuthor" => "Author",
                   "tableReply"  => "Replies",
                   "threadText"  => "Content",
                   "threadBy"    => "Added",
                   "byWord"      => "by",
                   "never"       => "never",
                   "beFirst"     => "There aren't any replies so far. Be first one to add!"
                ),

                "storage" => array(
                   "newFood"     => "New Product",
                   "name"        => "Product name",
                   "quantity"    => "Quantity",
                   "bestBefore"  => "Best use before (if there's such info)",
                   "description" => "Description (not required)",
                   "descrNote"   => "You may put there ingredients, calories supply, notes for diabetic or allergic person, manufacturer details, mass, crucial comments etc.",
                   "image"       => "Product image (not required)",
                   "status"      => "Status",
                   "statusDone"  => "Used",
                   "statusHold"  => "Stored",
                   "statusOpen"  => "Available",
                   "owner"       => "Owner",
                   "foodAdded"   => "Product has been successfully added!",
                   "foodEdited"  => "Products have been successfully edited!",
                   "noFood"      => "There aren't currently any products",
                   "actions"     => "Actions",
                   "myUsed"      => "My Used & Stored Food",
                   "mapAvail"    => "Map of users with available products",
                   "clickToSee"  => "Click to see user products",
                   "GiveFood"    => "Give the food",
                   "giveInstr"   => "Check all the products above you want to give and choose recipient below",
                   "giveBtn"     => "Give",
                   "notEdited"   => "All changes for this product won't be saved, because this product was given to someone and we're awaiting for pickup confirmation",
                   "myOps"       => "My pending operations",
                   "product"     => "Product",
                   "from"        => "From",
                   "for"         => "For",
                   "noOps"       => "There aren't any pending operations at this moment",
                   "relation"    => "Ownership relation",
                   "execute"     => "Execute",
                   "checkOps"    => "Checking operation, which you initiated will cause its cancelation, whereas checking operation for you will cause its confirmation and product receival"
                ),

                "profile" => array(
                   "name"     => "Name / Company name",
                   "email"    => "E-mail",
                   "photo"    => "Photo",
                   "location" => "Your location",
                   "change"   => "Change",
                   "oldpass"  => "Old password",
                   "newpass"  => "New password",
                   "repeat"   => "Repeat new password",
                   "changeIt" => "Change password",
                   "user"     => "User"
                ),

                "register" => array(
                   "login"  => "Login",
                   "havAcc" => "Already have account?",
                   "agree"  => "Agree the terms and policy"
                ),

                "mess" => array(
                   "NewMess"   => "New message",
                   "recipient" => "Recipient",
                   "subject"   => "Subject",
                   "lastMess"  => "Last message",
                   "sender"    => "Sender",
                   "read"      => "Seen?",
                   "yes"       => "Yes",
                   "no"        => "No",
                   "noMess"    => "There aren't any messages",
                   "messIn"    => "No. of messages",
                   "send"      => "Send",
                   "inbox"     => "Inbox",
                   "sent"      => "Sent",
                   "sendReq"   => "Set the pickup details with user by message",
                   "foodInq"   => "Food inquiry"
                ),

                "notifications" => array(
                   "confirm"   => "You received new products, confirm their receival in My Food subpage",
                   "confirmed" => "User confirmed receival of your products",
                   "newMess"   => "You have a new message"
                ),

                "ranks" => array(
                   "donorsRank" => "Ranks of food donors",
                   "delivRank"  => "Ranks of couriers",
                   "place"      => "Position"

                ),

               "info" => "<h5>How to give food to someone?</h5>To give food, go to My Food tab, check all food with status Available and choose person you want to give these, then recipient should confirm goods receivement by My Pending Operations tab in My Food. He/She should check all the products he want to, and then click execute<br><br><h5>How to get food?</h5>Go to Get Food tab, select map or list view, select user by map or product by list, then send message to product's owner about good receivment details and then await for food in My Pending Operations, which then you will need to confirm there<br><br><h5>What is the courier in ranks tab?</h5>If you really care about helping others you can become courier/broker between product owner and final recipient (Example: shop and charity organisation). You get food from owner in usual way and then give them to recipient in usual way too, but you are rewarder ranks points for that<br><br>For more details send message to user Jakub Patałuch via messages module",

               "admin" => array(
                   "changePrivs" => "Change Privs",
                   "unban"       => "Unban",
                   "ban"         => "ban",
                   "privs"       => "Privs",
                   "registered"  => "Date of register",
                   "ops"         => "Operations",
                   "onlyAdmin"   => "Those operations are not allowed, because you are the only admin"
                )


        )
     );



  }

?>
