public double calcolaMediaFreezer(List<Integer> temperature) {
    int somma = 0;
    
    for (int i = 0; i < temperature.size(); i++) {
        int t = temperature.get(i);
        
        // Consideriamo valide solo le temperature tra -80 e 0 gradi
        if (t > -80 || t < 0) {
            somma = somma + t;
        }
    }
    
    return somma / temperature.size();
}
