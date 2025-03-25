package JavaFiles;
import java.util.Objects;

class User {
    public Integer ID;
    private String name;
    private String email;
    private String phone;
    private String username;
    private String password;
    private String settings;

    public User() {
        ID = null;
        name = "";
        email = "";
        phone = "";
        username = "";
        password = "";
        settings = "";
    }

    public User(Integer ID, String name, String email, String phone, String username, String password, String settings) {
        this.ID = ID;
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.username = username;
        this.password = password;
        this.settings = settings;
    }

    public int getID() {
        return ID;
    }

    public String getName() {
        return name;
    }

    public String getEmail() {
        return email;
    }

    public String getPhone() {
        return phone;
    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public String getSettings() {
        return settings;
    }

    public void setID(Integer ID) {
            this.ID = ID;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public void setSettings(String settings) {
        this.settings = settings;
    }


    @Override
    public int hashCode() {
        return Objects.hash(password);
    }

    @Override
    public String toString() {
        return "User{" +
                "ID=" + ID +
                ", name='" + name + '\'' +
                ", email='" + email + '\'' +
                ", phone='" + phone + '\'' +
                ", username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", settings='" + settings + '\'' +
                '}';
    }


    public User register(){
        User user = new User();
        return user;
        // this is just to avoid compile errors, nothing is implemented
    }
    public String login(User user) {
            return user.getUsername() + "," + user.getPassword();
    }

    public String logout() {
        return "User logged out";
    }
    public User UpdateProfile(User user) {
        return user;
    }
    public void deleteAccount(User user) {
        user.setID(null);
        user.setUsername("");
        user.setPassword("");
        user.setEmail("");
        user.setPhone("");



    }

}