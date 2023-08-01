
#include <DHT.h>
#include <DHT_U.h>

#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

#define DHTTYPE    DHT11
#define DHT_PIN 2
#define TRIG_PIN 12
#define ECHO_PIN 14
#define ANALOG_PIN A0

const double SOUND_VELOCITY = 0.034;
const int SLEEP_TIME = 1000 * 30; // 1 minutes
const String ID = "sdf3-tyhm-48f4-bhio-74g4";
const String PASSWORD = "passwordxyz";

// TODO: wifi credentials here
char ssid[] = "";
char pass[] = ""; 
WiFiClient  client;

DHT dht(DHT_PIN, DHTTYPE);

void setup() {
  Serial.begin(9600);
  dht.begin();
  
  pinMode(BUILTIN_LED, OUTPUT);
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  
  WiFi.mode(WIFI_OFF);
  delay(1000);
  WiFi.mode(WIFI_STA);
  
  WiFi.begin(ssid, pass); 
  Serial.println("");
  Serial.print("Connecting");

  while (WiFi.status() != WL_CONNECTED) {
    digitalWrite(BUILTIN_LED, LOW);
    delay(250);
    digitalWrite(BUILTIN_LED, HIGH);
    delay(250);
  }

  digitalWrite(BUILTIN_LED, HIGH);
  Serial.print("Connected. IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  
  // first read sensor data
  float distance = 0.0;
  for (int i = 0; i < 15; i++){
    distance += getUltrasonicDistance();
    delayMicroseconds(50);
  }
  //average value
  distance = distance/15;
  Serial.print("Distance = ");
  Serial.println(distance);
  
  float flowRate = getFlowRate();
  Serial.print("Flow Rate = ");
  Serial.println(flowRate);
  
  float temperature = getTemperature();
  float humidity = getHumidity();
  /*
  Serial.print("Temp = ");
  Serial.println(temperature);
  Serial.print("Humidity = ");
  Serial.println(humidity);
  */
  // send data
  postData(distance, flowRate, temperature, humidity);
  Serial.println("");
  
  // sleep
  delay(SLEEP_TIME);
  
}

float getUltrasonicDistance(){
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);
  // Send pulse
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);
  digitalWrite(TRIG_PIN, LOW);
  
  // GET ECHO
  int duration = pulseIn(ECHO_PIN, HIGH);
  
  // Get distance
  float distanceCm = duration * SOUND_VELOCITY/2;

  return distanceCm;
}
float getFlowRate(){
  int val = analogRead(ANALOG_PIN);
  val = map(val, 0, 1023, 0, 100);
  return (float)val;
}
float getTemperature(){
  return dht.readTemperature();
}
float getHumidity(){
  return dht.readHumidity();
}


bool postData(float distance, float flowRate, float temperature, float humidity){
  HTTPClient http;
  // TODO: replace 192:168:43:147 with your computer IP
  http.begin(client, "http://192.168.43.156/flooding/web_service/post_log.php");
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  char dV_[4];
  sprintf(dV_, "%f", distance);
  String dVal = dV_;
  char fV_[4];
  sprintf(fV_, "%f", flowRate);
  String fVal = fV_;
  char tV_[4];
  sprintf(tV_, "%f", temperature);
  String tVal = tV_;
  char hV_[4];
  sprintf(hV_, "%f", humidity);
  String hVal = hV_;
  
  String httpRequestData = "";
  httpRequestData += "id="+ID;
  httpRequestData += "&password="+PASSWORD;
  httpRequestData += "&waterLevel="+dVal;
  httpRequestData += "&flowRate="+fVal;
  httpRequestData += "&temperature="+tVal;
  httpRequestData += "&humidity="+hVal;

  Serial.println(httpRequestData);
  int httpResponseCode = http.POST(httpRequestData);
  Serial.print("Response = ");
  Serial.println(httpResponseCode);

  http.end();  

  if (httpResponseCode == 200){
    return true;
  } else {
    return false;
  }
}
