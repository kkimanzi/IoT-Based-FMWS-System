# IoT-Based-FMWS-System
**Executive Summary**
The impact caused by floods is projected to be on an increase, with the number of people affected and the property damage caused by this is expected to increase exponentially. FMWS enable the early detection of floods, and the subsequent dissemination of warnings to individuals and/ or relevant bodies.  Literature review conducted found that existing FMWS projects suffered from a few drawbacks: reliability of sensor data, scalability and usability.
This project used a client-server architecture in the development of the FMWS. The clients in this project were comprised of the sensing layer – sensors and microcontrollers. The server was comprised of a website, web service and database. 
The sensing layer made use of ultrasonic sensor for water level detection, flow rate sensor, temperature and humidity sensors. The use of multiple sensors mitigated the anomalies of the ultrasonic sensor, and its accuracy shortcoming due to the effect of temperature gradients. The ESP 8266 microcontroller on the nodeMCU development board was used in the sensing layer. 
Sensor data read from the sensing layer is posted to the web layer via a web service. Authentication was performed to ensure authorized end-points post data to the service. Users were capable of being registered to the system via a website, allowing them to receive alerts on imminent and ongoing floods. Through the website, information was also provided on safety, preparedness and guidelines for imminent, ongoing and post floods.
Through an administrator dashboard, an administrator was capable to creating a location to be monitored on the system. The administrator was able to define the thresholds needed in the detection of floods as need, allowing the system to keep up with seasonal changes. Sensing devices were able to be registered to the FMWS, and deployed to particular locations.

**System Block Diagram**
![image](https://github.com/kkimanzi/IoT-Based-FMWS-System/assets/62201012/230a2e6c-fd94-4e94-b61a-f90236f10184)

**System Flow Chart**
![image](https://github.com/kkimanzi/IoT-Based-FMWS-System/assets/62201012/fd89d9db-340e-4db4-a29b-c4ad7b6d526b)


A Sample of Data Acquired from the Arduino-based Remote Sensors on the Server
![image](https://github.com/kkimanzi/IoT-Based-FMWS-System/assets/62201012/ec2a8b7a-e0f7-493f-9ec1-a290a2224569)

A Sample SMS warning sent on citizen's phone on flood detection
![image](https://github.com/kkimanzi/IoT-Based-FMWS-System/assets/62201012/7889bc3d-9645-4a9f-b830-faae95c49d30)



