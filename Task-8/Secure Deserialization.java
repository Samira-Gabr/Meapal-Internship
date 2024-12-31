import java.io.*;
import java.util.*;
//`java.io.*`: Provides the classes for input/output operations, including object serialization and deserialization
//`java.util.*`: Includes utilities for collections like `List`.
public class SecureDeserialization {
//Defines the main class named `SecureDeserialization`, encapsulating the logic for secure deserialization.
    public static void main(String[] args) {
       //The program's entry point, where deserialization logic is implemented.
        try {
            FileInputStream fileInputStream = new FileInputStream("data.ser");
            //Opens a file named `data.ser` for reading, Reads serialized data from the file, which is expected to contain a serialized object.
            ObjectInputStream objectInputStream = new ObjectInputStream(fileInputStream) {
                @Override
                protected Class<?> resolveClass(ObjectStreamClass desc) throws IOException, ClassNotFoundException {
                    String className = desc.getName();
                    if ("java.util.ArrayList".equals(className) || "java.util.LinkedList".equals(className)) {
                        return super.resolveClass(desc);
                    } else {
                        throw new ClassNotFoundException("Unauthorized class: " + className);
                    }
                }
            };
           //Custom `ObjectInputStream`:Overrides the `resolveClass` method to implement a whitelist-based class validation mechanism.
          //`resolveClass(ObjectStreamClass desc)`: Determines the class to use for deserialization.
          //`desc.getName()`: Retrieves the fully qualified name of the class being deserialized.
          //Whitelisting:Allows deserialization only for `java.util.ArrayList` and `java.util.LinkedList`.
          //Unauthorized Classes:If any other class is encountered, a `ClassNotFoundException` is thrown.
          //Purpose: Prevents deserialization of malicious or unexpected classes by enforcing strict control over what can be deserialized.

            Object deserializedObject = objectInputStream.readObject();
            objectInputStream.close();
            //Reads the serialized object from the file and reconstructs it in memory, Retrieves a trusted object, validated by the custom `resolveClass` method.
            if (deserializedObject instanceof List) {
                List<?> list = (List<?>) deserializedObject;
                for (Object obj : list) {
                    System.out.println(obj);
                }
            }
            //`instanceof List`:Checks if the deserialized object is of type `List`.
           // Casting and Iteration**:
               // - Casts the object to a `List<?>`.
              //  - Iterates through the list, printing each item to the console.
         //Purpose: Safely handles and processes the deserialized object if it's a `List`.
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}