//Deserialization Code
import java.io.*;
 class Person implements Serializable {
    String name;
    int    age ;
    Person(String name, int age) {
      this.name = name;
      this.age  = age ; 
    }
 }
public class DeserializationExample {
   public static void main(String[] args) {
      Person person = null;
      try {
      FileInputStream   filein = new FileInputStream("person.ser");
      ObjectInputStream in     = new ObjectInputStream(fileIn);
      person = (person) in.readObject();
      in.close();
      fileIn.close();
      System.out.println("Deserialization complete.");
      System.out.println("Name: " + person.name);
      System.out.println("Age: "  + person.age);
      } catch (IOException | ClassNotFoundException e) {
        e.printStackTrace();
      }
   }
}
