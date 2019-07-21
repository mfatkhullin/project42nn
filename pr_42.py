#!/usr/bin/env python
# coding: utf-8

# In[155]:


import numpy as np
import pandas as pd
from scipy.optimize import linprog


wFile="E:\\python\\0_jupiter\\Otchyot_po_Kategoriam_uslug_20190720.xlsx"
xl = pd.ExcelFile(wFile)

dPrice = pd.read_excel(xl, "price", index_col=[0])
dWork  = pd.read_excel(xl, "work", index_col=[0])
dFirm  = pd.read_excel(xl, "firm", index_col=[0])
dType  = pd.read_excel(xl, "type", index_col=[0])




pColumns= dPrice.columns.values
pIndex  = dPrice.index.values
np.append(pColumns,"Summ")
np.append(pIndex,"Summ")

#c = np.array([3,2])*-1  # maximize

#c = [0, 7, 0, 8, 8, 1, 8, 2, 7, 1, 0, 3, 5, 0, 4, 6, 8, 2, 1, 9, 4, 9, 10, 3, 0, 5, 2, 9, 5, 8, 3, 6, 3, 6, 10, 5, 2, 6, 1, 4, 5, 1, 6, 10, 3, 2, 2, 0, 4, 6, 2, 0, 8, 5, 6, 9, 2, 7, 1, 9, 1, 7, 5, 9, 6, 3, 4, 8, 2, 6, 7, 10, 4, 6, 3, 5, 3, 10, 4, 0, 3, 7, 6, 9, 10, 2, 3, 3, 6, 0, 6, 4, 6, 2, 0, 0, 7, 2, 7, 9, 5, 8, 4, 10, 0, 7, 1, 10, 0, 9, 1, 10]

def linpr(c,b_ub,b_eq):
    len_ub = len(b_ub) #3
    len_eq = len(b_eq) #4
    
    A_ub = np.tile(np.eye(len_ub), len_eq) #<= # 1 var
    A_eq = np.repeat(np.eye(len_eq), len_ub, axis=1)   #== # 1 var

    #print (A_eq)

    res = linprog(c, A_ub, b_ub, A_eq, b_eq)


    return(res.x.reshape((len_eq,len_ub)).astype(np.int64))
    

npOpt=np.empty((0,len_ub), int)


for index, row in dType.iterrows():
    c=dPrice.loc[index].ravel()
    b_ub=row.to_numpy().ravel()
    b_eq = dWork.loc[index].ravel()

    npOpt=np.append(npOpt, linpr(c,b_ub,b_eq), axis=0)


# In[ ]:





# In[ ]:





# In[ ]:





# In[156]:


npOptPrice=npOptim*dPrice.to_numpy()


# In[ ]:





# In[157]:


list_01=pd.DataFrame(npOptim, columns=pColumns, index = pIndex)


# In[ ]:





# In[170]:


list_01['Summ'] = list_01.sum(axis=1)
list_01.loc['Summ'] = list_01.sum(axis=0)


# In[171]:


list_01.head()


# In[172]:


list_02=pd.DataFrame(npOptPrice, columns=pColumns, index = pIndex)


# In[173]:


list_02['Summ'] = list_02.sum(axis=1)
list_02.loc['Summ'] = list_02.sum(axis=0)


# In[174]:


list_02.head()


# In[ ]:





# In[175]:


with pd.ExcelWriter('output.xlsx') as writer:  # doctest: +SKIP
    list_01.to_excel(writer, sheet_name='result')
    list_02.to_excel(writer, sheet_name='price')


# In[ ]:





# In[23]:


#list_01.to_json('file.json', orient='records', lines=True)


# In[25]:


#list_01.to_json()


# In[ ]:





# In[ ]:


c = dPrice.to_numpy().ravel()
b_ub = dFirm.to_numpy().ravel()
b_eq = dWork.to_numpy().ravel()


len_ub = len(b_ub) #3
len_eq = len(b_eq) #4

#A_eq = np.repeat(np.eye(len_eq), len_ub, axis=1)   #==
#A_ub = np.tile(np.eye(len_ub), len_eq) #<=

A_ub = np.tile(np.eye(len_ub), len_eq) #<= # 1 var
A_eq = np.repeat(np.eye(len_eq), len_ub, axis=1)   #== # 1 var

#print (A_eq)

res = linprog(c, A_ub, b_ub, A_eq, b_eq)
resX=res.x.reshape((len_eq,len_ub)).astype(np.int64)


# In[ ]:




