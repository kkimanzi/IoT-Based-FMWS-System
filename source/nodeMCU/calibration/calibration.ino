#define TRIG_PIN 12
#define ECHO_PIN 14
#define ANALOG_PIN A0


const double SOUND_VELOCITY = 0.034;
const int SLEEP_TIME = 1000 * 2; // 12 seconds

void setup() {
  Serial.begin(9600);
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
}

void loop() {
  // first read sensor data
  float distance = 0.0;
  for (int i = 0; i < 15; i++){
    distance += getUltrasonicDistance();
    delayMicroseconds(50);
  }
  Serial.print("Distance = ");
  Serial.println(distance/15);

  float flowRate = getFlowRate();
  Serial.print("Flow rate = ");
  Serial.println(flowRate);

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
