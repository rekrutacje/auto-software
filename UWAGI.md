Moje uwagi do zadania 1:
1. Zastosowanie bazy sqlite3 do przechowywania wiadomości zamiast przechowywanie bezpośrednio w plikach:
   - lepsza wydajność bazy danych, na potrzeby zadania wystarczy sqlite3
   - łatwiejsza manipulacja danymi, np. na potrzeby sortowania
   - zapewnienie spójności danych, np. poprzez użycie unikalności kolumny dla UUID <br>
     (jeżeli wymaganie co do używania plików tekstowych dla przechowywania wiadomości jest restrykcyjne, to oczywiście mogę dopisać kod) <br><br>

2. Pokrycie testami:
   - ./backend/src/Controller/MessageController.php - 97% lines
   - ./backend/src/Entity/Message.php - 58% lines

3. Nginx ustawiony jest tak, że:
   - na http://localhost:80 mamy frontend
   - na http://localhost:81 mamy backend Symfony

4. Postawienie środowiska: make build

5. Kilka uwag metodycznych:
   - miałem zachować minimalizm, więc w Symfony jest tylko to co potrzeba
   - nie bawiłem się i zostawiłem setery i getery, ale powinno się to pisać inaczej
   - nie wydzielałem logiki z kontrolerów do serwisów, bo to jest mała aplikacja, ale w normalnym projekcie bym to wydzielał
   - opcjonalnie dołączyłem testy jednostkowe do wszystkich kontrolerów
   - dodatkowo w testach załączyłem skrypty curl odpytujące api