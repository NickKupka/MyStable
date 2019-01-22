package mainpackage;

import java.util.Random;

public class LicenseKeyGenerator {

  public static final int i_LENGTH = 18;
  public static final String s_SEPERATOR = "-";
  public static final String s_AllCHARACKTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
  public static Random rnd = new Random();
  public static StringBuilder salt = new StringBuilder();
  public static int i_countToFour = 0;

  public static String getLicenseKey() {
    while (salt.length() <= i_LENGTH) {
      if (i_countToFour == 4) {
        salt.append("-");
        i_countToFour = 0;
      } else {
        int index = (int) (rnd.nextFloat() * s_AllCHARACKTERS.length());
        salt.append(s_AllCHARACKTERS.charAt(index));
        i_countToFour++;
      }
    }
    return salt.toString();
  }

  public static void main(String[] args) {
    System.out.println(getLicenseKey());
  }
}
