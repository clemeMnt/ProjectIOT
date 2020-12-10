#include "DHTesp.h"
#include <LiquidCrystal.h>
#include <ESP8266WiFi.h>

LiquidCrystal lcd(4, 0, 15, 13, 12, 14);

#define DHTPIN 5
#define DHTTYPE DHT11

const char* host = "10.3.141.1";  // IP du serveur - Server IP
const int   port = 80;

DHTesp dht;

float t = 0.0;
float h = 0.0;

void setup() {
  dht.setup(DHTPIN, DHTesp::DHT11);
  lcd.begin(16, 2);
  Serial.begin(9600);

  WiFi.begin("SmartWine", "WineSmartpwd");

  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println();

  Serial.print("Connected, IP address: ");
  Serial.println(WiFi.localIP());


}
void loop() {
  TempAndHumidity lastValues = dht.getTempAndHumidity();

  Serial.println("Temperature: " + String(lastValues.temperature, 0));
  Serial.println("Humidity: " + String(lastValues.humidity, 0));

  lcd.setCursor(0, 0);
  lcd.print("Temp:          ");
  lcd.setCursor(6, 0);
  lcd.print(lastValues.temperature);
  lcd.setCursor(0, 1);
  lcd.print("Humidity:      ");
  lcd.setCursor(10, 1);
  lcd.print(lastValues.humidity);

  WiFiClient client;

  if (!client.connect(host, port)) {
    Serial.println("connection failed");
    return;
  }

  String url = "/capteur.php?temp=";
  url += lastValues.temperature;
  url += "&hygro=";
  url += lastValues.humidity;

  // Envoi la requete au serveur - This will send the request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");
  unsigned long timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println(">>> Client Timeout !");
      client.stop();
      return;
    }
  }

  // Read all the lines of the reply from server and print them to Serial
  while (client.available()) {
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }

  delay(1000);
}
