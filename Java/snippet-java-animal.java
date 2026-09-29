class Animal {

    protected String name;

    public Animal(String name) {
        this.name = name;
    }

    public void speak() {
        System.out.println(name + " makes a sound");
    }
}

class Dog extends Animal {

    public Dog(String name) {
        super(name);
    }

    @Override
    public void speak() {
        System.out.println(name + " says: Woof!");
    }
}

class Cat extends Animal {

    public Cat(String name) {
        super(name);
    }

    @Override
    public void speak() {
        System.out.println(name + " says: Meow!");
    }
}

public class Main {

    public static void main(String[] args) {

        Animal animal1 = new Dog("Rex");
        Animal animal2 = new Cat("Milo");

        animal1.speak();
        animal2.speak();

        Animal animal3 = new Dog("Buddy");
        animal3.speak();
    }
}
