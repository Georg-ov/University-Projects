package org.example.www.validaciones;

import java.util.regex.*;

public class ValidacionesSkeleton {
    
    public ValidarNIFResponse validarNIF(ValidarNIF validarNIF) {
        String nif = validarNIF.getIn();
        boolean esValido = validarNIF(nif);
        ValidarNIFResponse response = new ValidarNIFResponse();
        response.setOut(Boolean.toString(esValido));
        return response;
    }

    public ValidarNIEResponse validarNIE(ValidarNIE validarNIE) {
        String nie = validarNIE.getIn();
        boolean esValido = validarNIE(nie);
        ValidarNIEResponse response = new ValidarNIEResponse();
        response.setOut(Boolean.toString(esValido));
        return response;
    }

    public ValidarNAFResponse validarNAF(ValidarNAF validarNAF) {
        String naf = validarNAF.getIn();
        boolean esValido = validarNAF(naf);
        ValidarNAFResponse response = new ValidarNAFResponse();
        response.setOut(Boolean.toString(esValido));
        return response;
    }

    public ValidarIBANResponse validarIBAN(ValidarIBAN validarIBAN) {
        String iban = validarIBAN.getIn();
        boolean esValido = validarIBAN(iban);
        ValidarIBANResponse response = new ValidarIBANResponse();
        response.setOut(Boolean.toString(esValido));
        return response;
    }

    private boolean validarNIF(String nif) {
        if (!nif.matches("\\d{8}[A-Z]")) return false;
        String letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        int numero = Integer.parseInt(nif.substring(0, 8));
        char letraCalculada = letras.charAt(numero % 23);
        return letraCalculada == nif.charAt(8);
    }

    private boolean validarNIE(String nie) {
        if (!nie.matches("[XYZ]\\d{7}[A-Z]")) return false;
        char primeraLetra = nie.charAt(0);
        String numero;
        
        switch (primeraLetra) {
            case 'X':
                numero = "0" + nie.substring(1, 8);
                break;
            case 'Y':
                numero = "1" + nie.substring(1, 8);
                break;
            case 'Z':
                numero = "2" + nie.substring(1, 8);
                break;
            default:
                throw new IllegalArgumentException("Formato de NIE incorrecto");
        }

        return validarNIF(numero + nie.charAt(8));
    }


    private boolean validarNAF(String naf) {
        return naf.matches("\\d{12}");
    }

    private boolean validarIBAN(String iban) {
        if (!iban.matches("[A-Z]{2}\\d{22}")) return false;
        String reformatted = iban.substring(4) + iban.substring(0, 4);
        String numericIban = reformatted.chars()
            .mapToObj(c -> Character.isDigit(c) ? String.valueOf((char) c) : String.valueOf((char) c - 'A' + 10))
            .reduce("", String::concat);
        return new java.math.BigInteger(numericIban).mod(java.math.BigInteger.valueOf(97)).intValue() == 1;
    }
}
