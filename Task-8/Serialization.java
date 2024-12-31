//Serialization Code
import java.io.*;

class Person implements Serializable {
  String name;
  int    age;
  Person(String name, int age) {
     this.name = name;
     this.age  = age ; 
  }
}

public class SerializationExample {
  public static void main(String[] args) {
    Person person = new Person("John", 30);
    try {
    FileOutputStream fileOut = new FileOutputStream("person.ser");
    ObjectOutputStream out   = new ObjectOutputStream("fileout");
    out.writeObject(person);
    out.close();
    fileOut.close();
    System.out.println("Serialization complete. The object is saved to person.ser")
    } catch (IOException i) {
       i.printStackTrace();
    }
  }
}
