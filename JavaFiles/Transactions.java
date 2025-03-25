package JavaFiles;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

class Transactions{
    private Integer transactionID;
    private float amount;
    private String date;
    private String category; //expense or income
    private  String note;
    private Boolean isRecurring;
    public Integer userID;
    public Integer categoryID;

    public Transactions() {
        transactionID = null;
        amount = 0;
        date = null;
        category = null;
        note = null;
        isRecurring = null;
        userID = null;
        categoryID = null;
    }

    public Transactions(Integer transactionID, float amount, String date, String category, String note, Boolean isRecurring, Integer userID, Integer categoryID) {
        this.transactionID = transactionID;
        this.amount = amount;
        this.date = date;
        this.category = category;
        this.note = note;
        this.isRecurring = isRecurring;
        this.userID = userID;
        this.categoryID = categoryID;
    }

    public Integer getTransactionID() {
        return transactionID;
    }

    public void setTransactionID(Integer transactionID) {
        this.transactionID = transactionID;
    }

    public float getAmount() {
        return amount;
    }

    public void setAmount(float amount) {
        this.amount = amount;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getCategory() {
        return category;
    }

    public void setCategory(String category) {
        this.category = category;
    }

    public String getNote() {
        return note;
    }

    public void setNote(String note) {
        this.note = note;
    }

    public Boolean getRecurring() {
        return isRecurring;
    }

    public void setRecurring(Boolean recurring) {
        isRecurring = recurring;
    }

    public Integer getUserID() {
        return userID;
    }

    public void setUserID(Integer userID) {
        this.userID = userID;
    }

    public Integer getCategoryID() {
        return categoryID;
    }

    public void setCategoryID(Integer categoryID) {
        this.categoryID = categoryID;
    }

    public Transactions addTransaction(Transactions transaction) {
        return transaction;
    }

    public Transactions editTransaction(Transactions transaction) {
        return transaction;
    }
    public void deleteTransaction(Transactions transaction) {

    }

    public static List<Transactions> getTransactionHistory(User user) {
        List<Transactions> transactionsList = new ArrayList<>();

        String url = "jdbc:mysql://localhost:3306/your_database"; // Replace with your DB URL
        String username = "your_username"; // Replace with your DB username
        String password = "your_password"; // Replace with your DB password

        String query = "SELECT * FROM transactions WHERE userID = ?";

        try (Connection conn = DriverManager.getConnection(url, username, password);
             PreparedStatement pstmt = conn.prepareStatement(query)) {

            pstmt.setInt(1, user.getID());
            ResultSet rs = pstmt.executeQuery();

            while (rs.next()) {
                Transactions transaction = new Transactions(
                        rs.getInt("transactionID"),
                        rs.getFloat("amount"),
                        rs.getString("date"),
                        rs.getString("category"),
                        rs.getString("note"),
                        rs.getBoolean("isRecurring"),
                        rs.getInt("userID"),
                        rs.getInt("categoryID")
                );
                transactionsList.add(transaction);
            }

        } catch (SQLException e) {
            e.printStackTrace();
        }

        return transactionsList;
    }

    @Override
    public String toString() {
        return "Transactions{" +
                "transactionID=" + transactionID +
                ", amount=" + amount +
                ", date=" + date +
                ", category='" + category + '\'' +
                ", note='" + note + '\'' +
                ", isRecurring=" + isRecurring +
                ", userID=" + userID +
                ", categoryID=" + categoryID +
                '}';
    }
}