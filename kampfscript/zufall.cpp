#include "zufall.h"

/* get integer random number in range a <= x <= e */
int irand(int a, int e)
{
    double r = e - a + 1;
    return a + (int)(r * rand()/(RAND_MAX+1.0));
}
